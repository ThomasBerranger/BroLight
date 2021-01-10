<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AvatarRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Avatar
{
    const ACCESSORIES_TYPE = [
        'Aucun' => 'Blank',
        'Lunette 1' => 'Kurt',
        'Lunette 2' => 'Prescription01',
        'Lunette 3' => 'Prescription02',
        'Lunette 4' => 'Round',
        'Lunette 5' => 'Sunglasses',
        'Lunette 6' => 'Wayfarers',
    ];

    const CLOTHE_COLOR = [
        'Noir' => 'Black',
        'Gris clair' => 'Gray01',
        'Gris' => 'Gray02',
        'Blue Pastel' => 'PastelBlue',
        'Bleu clair' => 'Blue01',
        'Bleu marin' => 'Blue02',
        'Bleu foncé' => 'Blue03',
        'Bleu Oxford' => 'Heather',
        'Vert' => 'PastelGreen',
        'Orange' => 'PastelOrange',
        'Rouge' => 'Red',
        'Rouge Pastel' => 'PastelRed',
        'Jaune' => 'PastelYellow',
        'Rose' => 'Pink',
        'Blanc' => 'White',
    ];

    const CLOTHE_TYPE = [
        'Veste' => 'BlazerShirt',
        'Veste et colle' => 'BlazerSweater',
        'Colle' => 'CollarSweater',
        'T-shirt avec graphique' => 'GraphicShirt',
        'Pull à capuche' => 'Hoodie',
        'Salopette' => 'Overall',
        'T-shirt' => 'ShirtCrewNeck',
        'T-shirt colle large' => 'ShirtScoopNeck',
        'T-shirt colle en V' => 'ShirtVNeck',
    ];

    const FACIAL_HAIR_TYPE = [
        'Aucune' => 'Blank',
        'Barbe courte' => 'BeardLight',
        'Barbe moyenne' => 'BeardMedium',
        'Barbe majestueuse' => 'BeardMajestic',
        'Moustache fantaisiste' => 'MoustacheFancy',
        'Moustache Magnum' => 'MoustacheMagnum',
    ];

    const EYE_TYPE = [
        'Fermés' => 'Close',
        'Tristes' => 'Cry',
        'Normaux' => 'Default',
        'En croix' => 'Dizzy',
        'Levés' => 'EyeRoll',
        'Heureux' => 'Happy',
        'En coeur' => 'Hearts',
        'Sournois' => 'Side',
        'Louches' => 'Squint',
        'Supris' => 'Surprised',
        'Clinc d\'œil' => 'Wink',
        'Clinc d\'œil 2' => 'WinkWacky',
    ];

    const EYEBROW_TYPE = [
        'Fachés' => 'Angry',
        'Fachés 2' => 'AngryNatural',
        'Normaux' => 'Default',
        'Normaux 2' => 'DefaultNatural',
        'Plats' => 'FlatNatural',
        'Excités' => 'RaisedExcited',
        'Excités 2' => 'RaisedExcitedNatural',
        'Tristes' => 'SadConcerned',
        'Tristes 2' => 'SadConcernedNatural',
        'Mono' => 'UnibrowNatural',
        'En haut en bas' => 'UpDown',
        'En haut en bas 2' => 'UpDownNatural',
    ];

    const FACIAL_HAIR_COLOR = [
        'Auburn' => 'Auburn',
        'Noire' => 'Black',
        'Blonde' => 'Blonde',
        'Blonde or' => 'BlondeGolden',
        'Marron' => 'Brown',
        'Marron foncée' => 'BrownDark',
        'Platine' => 'Platinum',
        'Rouge' => 'Red',
    ];

    const GRAPHIC_TYPE = [
        'Chauve souris' => 'Bat',
        'Cumbia' => 'Cumbia',
        'Cerf' => 'Deer',
        'Diamant' => 'Diamond',
        'Hola' => 'Hola',
        'Pizza' => 'Pizza',
        'Resist' => 'Resist',
        'Selena' => 'Selena',
        'Ours' => 'Bear',
        'Crâne' => 'SkullOutline',
        'Crâne 2' => 'Skull',
    ];

    const HAIR_COLOR =[
        'Auburn' => 'Auburn',
        'Noir' => 'Black',
        'Blonds' => 'Blonde',
        'Blonds Or' => 'BlondeGolden',
        'Marrons' => 'Brown',
        'Marrons foncés' => 'BrownDark',
        'Roses' => 'PastelPink',
        'Platines' => 'Platinum',
        'Rouges' => 'Red',
        'Gris argent' => 'SilverGray',
    ];

    const HAT_COLOR =[
        'Noir' => 'Black',
        'Gris clair' => 'Gray01',
        'Gris' => 'Gray02',
        'Blue Pastel' => 'PastelBlue',
        'Bleu clair' => 'Blue01',
        'Bleu marin' => 'Blue02',
        'Bleu foncé' => 'Blue03',
        'Bleu Oxford' => 'Heather',
        'Vert' => 'PastelGreen',
        'Orange' => 'PastelOrange',
        'Rouge' => 'Red',
        'Rouge Pastel' => 'PastelRed',
        'Jaune' => 'PastelYellow',
        'Rose' => 'Pink',
        'White' => 'White',
    ];

    const MOUTH_TYPE = [
        'Bouche bée' => "Concerned",
        'Normale' => "Default",
        'Normale inversée' => "Disbelief",
        'Gosses joues' => "Eating",
        'Grimace' => "Grimace",
        'Triste' => "Sad",
        'Hurlement' => "ScreamOpen",
        'Sérieuse' => "Serious",
        'Souriante 1' => "Twinkle",
        'Souriante 2' => "Smile",
        'Tire langue' => "Tongue",
        'Vomis' => "Vomit",
    ];

    const SKIN_COLOR = [
        'Tannée' => 'Tanned',
        'Jaune' => 'Yellow',
        'Blanche' => 'Pale',
        'Bronzée' => 'Light',
        'Marron' => 'Brown',
        'Marron foncée' => 'DarkBrown',
        'Noire' => 'Black',
    ];

    const TOP_TYPE = [
        'Chauve' => 'NoHair',
        'Bandeau de pirate' => 'Eyepatch',
        'Chapeau' => 'Hat',
        'Voile' => 'Hijab',
        'Turban' => 'Turban',
        'Chapeau d\'hiver 1' => 'WinterHat1',
        'Chapeau d\'hiver 2' => 'WinterHat2',
        'Chapeau d\'hiver 3' => 'WinterHat3',
        'Chapeau d\'hiver 4' => 'WinterHat4',
        'Cheveux très longs' => 'LongHairBigHair',
        'Cheveux mis longs' => 'LongHairBob',
        'Chignon' => 'LongHairBun',
        'Cheveux bouclés' => 'LongHairCurly',
        'Cheveux courbés' => 'LongHairCurvy',
        'Dreadlocks' => 'LongHairDreads',
        'Fleurs' => 'LongHairFrida',
        'Coupe affro' => 'LongHairFro',
        'Coupe affro bandeau' => 'LongHairFroBand',
        'Cheveux mis longs raides' => 'LongHairNotTooLong',
        'Cheveux longs de côté' => 'LongHairShavedSides',
        'Mia Wallace' => 'LongHairMiaWallace',
        'Cheveux longs raides' => 'LongHairStraight',
        'Cheveux longs raides 2' => 'LongHairStraight2',
        'Cheveux longs raides 3' => 'LongHairStraightStrand',
        'Dreadlocks 1' => 'ShortHairDreads01',
        'Dreadlocks 2' => 'ShortHairDreads02',
        'Cheveux courts frisés' => 'ShortHairFrizzle',
        'Mullet 2' => 'ShortHairShaggyMullet',
        'Cheveux courts bouclés' => 'ShortHairShortCurly',
        'Cheveux courts plats 1' => 'ShortHairShortFlat',
        'Cheveux courts plats 2' => 'ShortHairShortRound',
        'Cheveux courts plats 3' => 'ShortHairShortWaved',
        'Calvisi' => 'ShortHairSides',
        'Cheveux rasés 1' => 'ShortHairTheCaesar',
        'Cheveux rasés 2' => 'ShortHairTheCaesarSidePart',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"avatar:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="avatar")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"avatar:read"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $accessoriesType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $avatarStyle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $clotheColor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $clotheType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $eyeType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $eyebrowType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $facialHairColor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $facialHairType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $graphicType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $hairColor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $hatColor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $mouthType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $skinColor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"avatar:read", "user:read"})
     */
    private $topType;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"avatar:read", "user:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"avatar:read", "user:read"})
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAccessoriesType(): ?string
    {
        return $this->accessoriesType;
    }

    public function setAccessoriesType(string $accessoriesType): self
    {
        $this->accessoriesType = $accessoriesType;

        return $this;
    }

    public function getAvatarStyle(): ?string
    {
        return $this->avatarStyle;
    }

    public function setAvatarStyle(string $avatarStyle): self
    {
        $this->avatarStyle = $avatarStyle;

        return $this;
    }

    public function getClotheColor(): ?string
    {
        return $this->clotheColor;
    }

    public function setClotheColor(string $clotheColor): self
    {
        $this->clotheColor = $clotheColor;

        return $this;
    }

    public function getClotheType(): ?string
    {
        return $this->clotheType;
    }

    public function setClotheType(string $clotheType): self
    {
        $this->clotheType = $clotheType;

        return $this;
    }

    public function getEyeType(): ?string
    {
        return $this->eyeType;
    }

    public function setEyeType(string $eyeType): self
    {
        $this->eyeType = $eyeType;

        return $this;
    }

    public function getEyebrowType(): ?string
    {
        return $this->eyebrowType;
    }

    public function setEyebrowType(string $eyebrowType): self
    {
        $this->eyebrowType = $eyebrowType;

        return $this;
    }

    public function getFacialHairColor(): ?string
    {
        return $this->facialHairColor;
    }

    public function setFacialHairColor(string $facialHairColor): self
    {
        $this->facialHairColor = $facialHairColor;

        return $this;
    }

    public function getFacialHairType(): ?string
    {
        return $this->facialHairType;
    }

    public function setFacialHairType(string $facialHairType): self
    {
        $this->facialHairType = $facialHairType;

        return $this;
    }

    public function getGraphicType(): ?string
    {
        return $this->graphicType;
    }

    public function setGraphicType(string $graphicType): self
    {
        $this->graphicType = $graphicType;

        return $this;
    }

    public function getHairColor(): ?string
    {
        return $this->hairColor;
    }

    public function setHairColor(string $hairColor): self
    {
        $this->hairColor = $hairColor;

        return $this;
    }

    public function getHatColor(): ?string
    {
        return $this->hatColor;
    }

    public function setHatColor(string $hatColor): self
    {
        $this->hatColor = $hatColor;

        return $this;
    }

    public function getMouthType(): ?string
    {
        return $this->mouthType;
    }

    public function setMouthType(string $mouthType): self
    {
        $this->mouthType = $mouthType;

        return $this;
    }

    public function getSkinColor(): ?string
    {
        return $this->skinColor;
    }

    public function setSkinColor(string $skinColor): self
    {
        $this->skinColor = $skinColor;

        return $this;
    }

    public function getTopType(): ?string
    {
        return $this->topType;
    }

    public function setTopType(string $topType): self
    {
        $this->topType = $topType;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreateDefaultValues()
    {
        $this->accessoriesType = self::ACCESSORIES_TYPE[array_rand(self::ACCESSORIES_TYPE)];
        $this->clotheColor = self::CLOTHE_COLOR[array_rand(self::CLOTHE_COLOR)];
        $this->clotheType = self::CLOTHE_TYPE[array_rand(self::CLOTHE_TYPE)];
        $this->eyeType = self::EYE_TYPE[array_rand(self::EYE_TYPE)];
        $this->eyebrowType = self::EYEBROW_TYPE[array_rand(self::EYEBROW_TYPE)];
        $this->facialHairColor = self::FACIAL_HAIR_COLOR[array_rand(self::FACIAL_HAIR_COLOR)];
        $this->facialHairType = self::FACIAL_HAIR_TYPE[array_rand(self::FACIAL_HAIR_TYPE)];
        $this->graphicType = self::GRAPHIC_TYPE[array_rand(self::GRAPHIC_TYPE)];
        $this->hairColor = self::HAT_COLOR[array_rand(self::HAT_COLOR)];
        $this->hatColor = self::HAT_COLOR[array_rand(self::HAT_COLOR)];
        $this->mouthType = self::MOUTH_TYPE[array_rand(self::MOUTH_TYPE)];
        $this->skinColor = self::SKIN_COLOR[array_rand(self::SKIN_COLOR)];
        $this->topType = self::TOP_TYPE[array_rand(self::TOP_TYPE)];
        $this->avatarStyle = 'Transparent';
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdateDefaultValues()
    {
        $this->updatedAt = new \DateTime();
    }

}
