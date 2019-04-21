<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $creeA;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Astreinte", mappedBy="utilisateur", orphanRemoval=true)
     */
    private $astreintes;

    public function __construct()
    {
        $this->astreintes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCreeA(): ?\DateTimeInterface
    {
        return $this->creeA;
    }

    public function setCreeA(\DateTimeInterface $creeA): self
    {
        $this->creeA = $creeA;

        return $this;
    }

    /**
     * @return Collection|Astreinte[]
     */
    public function getAstreintes(): Collection
    {
        return $this->astreintes;
    }

    public function addAstreinte(Astreinte $astreinte): self
    {
        if (!$this->astreintes->contains($astreinte)) {
            $this->astreintes[] = $astreinte;
            $astreinte->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAstreinte(Astreinte $astreinte): self
    {
        if ($this->astreintes->contains($astreinte)) {
            $this->astreintes->removeElement($astreinte);
            // set the owning side to null (unless already changed)
            if ($astreinte->getUtilisateur() === $this) {
                $astreinte->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials(){ }

    public function getSalt(){ }


    public function toString(){
        return $this->getPrenom() . " " . $this->getNom();
    }

    
    // public function SpaceNum(){
    //     $str=$this->getNumero();
    //     if(strlen($str) == 10) {
    //         $res = substr($str, 0, 2) .' ';
    //         $res .= substr($str, 2, 2) .' ';
    //         $res .= substr($str, 4, 2) .' ';
    //         $res .= substr($str, 6, 2) .' ';
    //         $res .= substr($str, 8, 2) .' ';
    //         return $res;
    //     }

    //}

    function SpaceNum()
    {
        $return = "";
        foreach (str_split($this->numero) as $i => $char){
            if($i % 2 == 0) $return .= " ";
            $return .= $char;
        }
        return $return;
    }
}