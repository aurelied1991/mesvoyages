<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: VisiteRepository::class)]
#[Vich\Uploadable]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 50)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    //Assert pour exiger que la date soit aujourd'hui ou antérieure
    #[Assert\LessThanOrEqual("now")]
    private ?\DateTime $datecreation = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 20)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avis = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempmin = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThan(propertyPath:"tempmin")] // Ajout d'une restriction pour que temps max soit supérieur à tempmin
    private ?int $tempmax = null;
    
    #[Vich\UploadableField(mapping: 'visites', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;
    
    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;
    
    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Environnement>
     */
    #[ORM\ManyToMany(targetEntity: Environnement::class)]
    private Collection $environnements;

    public function __construct()
    {
        $this->environnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getDatecreation(): ?\DateTime
    {
        return $this->datecreation;
    }
    
    //Méthode qui permet de récupérer la date au format string et l'afficher autrement
    public function getDatecreationString() : string
    {
        if($this->datecreation == null){
            return "";
        }else{
            return $this->datecreation->format('d/m/Y');
        }
    }

    public function setDatecreation(?\DateTime $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(?string $avis): static
    {
        $this->avis = $avis;

        return $this;
    }

    public function getTempmin(): ?int
    {
        return $this->tempmin;
    }

    public function setTempmin(?int $tempmin): static
    {
        $this->tempmin = $tempmin;

        return $this;
    }

    public function getTempmax(): ?int
    {
        return $this->tempmax;
    }

    public function setTempmax(?int $tempmax): static
    {
        $this->tempmax = $tempmax;

        return $this;
    }

    /**
     * @return Collection<int, Environnement>
     */
    public function getEnvironnements(): Collection
    {
        return $this->environnements;
    }

    public function addEnvironnement(Environnement $environnement): static
    {
        if (!$this->environnements->contains($environnement)) {
            $this->environnements->add($environnement);
        }

        return $this;
    }

    public function removeEnvironnement(Environnement $environnement): static
    {
        $this->environnements->removeElement($environnement);

        return $this;
    }
    
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    public function getImageName(): ?string {
        return $this->imageName;
    }

    public function getImageSize(): ?int {
        return $this->imageSize;
    }

    public function setImageFile(?File $imageFile): void {
        $this->imageFile = $imageFile;
        //permet de faire en sorte que le fichier fonctionne même s'il n'y a aucune autre modif
        if(null !== $imageFile){
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setImageName(?string $imageName): void {
        $this->imageName = $imageName;
    }

    public function setImageSize(?int $imageSize): void {
        $this->imageSize = $imageSize;
    }
    
    /**
     * Méthode appelée lors du clic sur le bouton Submit du formulaire
     * Permet affichage d'un message d'erreur personnalisé si le test non concluant + bloquer soumission formulaire
     * @param ExecutionContextInterface $context
     */
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context){
        $file = $this->getImageFile();
        if($file != null && $file != ""){
            //@ évite une erreur d'exécution : si méthode échoue elle retourne false
            $poids = @filesize($file);
            if($poids != false && $poids > 512000){
                $context->buildViolation("Cette image est trop lourde (500Ko max)")
                        ->atPath('imageFile')
                        ->addViolation();
            }
            //Ne pas confondre avec GetImageSize, renvoie tableau de 7 cases contenant hauteur, largeur, mime...
            $infosImage = @getimagesize($file);
            if($infosImage == false){
                $context->buildViolation("Ce fichier n'est pas une image")
                        ->atPath('imageFile')
                        ->addViolation();
            }
        }
    }
}
