<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvatarRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Avatar
{
    const ACCESSORIES_TYPE = [
        'Aucun' => 'Blank',
        'Lunettes 1' => 'Kurt',
        'Lunettes 2' => 'Prescription01',
        'Lunettes 3' => 'Prescription02',
        'Lunettes 4' => 'Round',
        'Lunettes 5' => 'Sunglasses',
        'Lunettes 6' => 'Wayfarers',
    ];

    const CLOTHE_COLOR = [
        'Black' => 'Black',
        'Blue01' => 'Blue01',
        'Blue02' => 'Blue02',
        'Blue03' => 'Blue03',
        'Gray01' => 'Gray01',
        'Gray02' => 'Gray02',
        'Heather' => 'Heather',
        'PastelBlue' => 'PastelBlue',
        'PastelGreen' => 'PastelGreen',
        'PastelOrange' => 'PastelOrange',
        'PastelRed' => 'PastelRed',
        'PastelYellow' => 'PastelYellow',
        'Pink' => 'Pink',
        'Red' => 'Red',
        'White' => 'White',
    ];

    const CLOTHE_TYPE = [
        'BlazerShirt' => 'BlazerShirt',
        'BlazerSweater' => 'BlazerSweater',
        'CollarSweater' => 'CollarSweater',
        'GraphicShirt' => 'GraphicShirt',
        'Hoodie' => 'Hoodie',
        'Overall' => 'Overall',
        'ShirtCrewNeck' => 'ShirtCrewNeck',
        'ShirtScoopNeck' => 'ShirtScoopNeck',
        'ShirtVNeck' => 'ShirtVNeck',
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
        'Close' => 'Close',
        'Cry' => 'Cry',
        'Default' => 'Default',
        'Dizzy' => 'Dizzy',
        'EyeRoll' => 'EyeRoll',
        'Happy' => 'Happy',
        'Hearts' => 'Hearts',
        'Side' => 'Side',
        'Squint' => 'Squint',
        'Surprised' => 'Surprised',
        'Wink' => 'Wink',
        'WinkWacky' => 'WinkWacky',
    ];

    const EYEBROW_TYPE = [
        'Angry' => 'Angry',
        'AngryNatural' => 'AngryNatural',
        'Default' => 'Default',
        'DefaultNatural' => 'DefaultNatural',
        'FlatNatural' => 'FlatNatural',
        'RaisedExcited' => 'RaisedExcited',
        'RaisedExcitedNatural' => 'RaisedExcitedNatural',
        'SadConcerned' => 'SadConcerned',
        'SadConcernedNatural' => 'SadConcernedNatural',
        'UnibrowNatural' => 'UnibrowNatural',
        'UpDown' => 'UpDown',
        'UpDownNatural' => 'UpDownNatural',
    ];

    const FACIAL_HAIR_COLOR = [
        'Auburn' => 'Auburn',
        'Black' => 'Black',
        'Blonde' => 'Blonde',
        'BlondeGolden' => 'BlondeGolden',
        'Brown' => 'Brown',
        'BrownDark' => 'BrownDark',
        'Platinum' => 'Platinum',
        'Red' => 'Red',
    ];

    const GRAPHIC_TYPE = [
        'Bat' => 'Bat',
        'Cumbia' => 'Cumbia',
        'Deer' => 'Deer',
        'Diamond' => 'Diamond',
        'Hola' => 'Hola',
        'Pizza' => 'Pizza',
        'Resist' => 'Resist',
        'Selena' => 'Selena',
        'Bear' => 'Bear',
        'SkullOutline' => 'SkullOutline',
        'Skull' => 'Skull',
    ];

    const HAIR_COLOR =[
        'Auburn' => 'Auburn',
        'Black' => 'Black',
        'Blonde' => 'Blonde',
        'BlondeGolden' => 'BlondeGolden',
        'Brown' => 'Brown',
        'BrownDark' => 'BrownDark',
        'PastelPink' => 'PastelPink',
        'Platinum' => 'Platinum',
        'Red' => 'Red',
        'SilverGray' => 'SilverGray',
    ];

    const HAT_COLOR =[
        'Black' => 'Black',
        'Blue01' => 'Blue01',
        'Blue02' => 'Blue02',
        'Blue03' => 'Blue03',
        'Gray01' => 'Gray01',
        'Gray02' => 'Gray02',
        'Heather' => 'Heather',
        'PastelBlue' => 'PastelBlue',
        'PastelGreen' => 'PastelGreen',
        'PastelOrange' => 'PastelOrange',
        'PastelRed' => 'PastelRed',
        'PastelYellow' => 'PastelYellow',
        'Pink' => 'Pink',
        'Red' => 'Red',
        'White' => 'White',
    ];

    const MOUTH_TYPE = [
        'Concerned' => "Concerned",
        'Default' => "Default",
        'Disbelief' => "Disbelief",
        'Eating' => "Eating",
        'Grimace' => "Grimace",
        'Sad' => "Sad",
        'ScreamOpen' => "ScreamOpen",
        'Serious' => "Serious",
        'Smile' => "Smile",
        'Tongue' => "Tongue",
        'Twinkle' => "Twinkle",
        'Vomit' => "Vomit",
    ];

    const SKIN_COLOR = [
        'Tanned' => 'Tanned',
        'Yellow' => 'Yellow',
        'Pale' => 'Pale',
        'Light' => 'Light',
        'Brown' => 'Brown',
        'DarkBrown' => 'DarkBrown',
        'Black' => 'Black',
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
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="avatar", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accessoriesType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatarStyle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clotheColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clotheType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $eyeType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $eyebrowType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facialHairColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facialHairType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $graphicType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hairColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hatColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mouthType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $skinColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $topType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
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
//        $this->setAuthor($this->container->get('security.token_storage')->getToken()->getUser());
        $this->accessoriesType = array_rand(self::ACCESSORIES_TYPE);
        $this->clotheColor = array_rand(self::CLOTHE_COLOR);
        $this->clotheType = array_rand(self::CLOTHE_TYPE);
        $this->eyeType = array_rand(self::EYE_TYPE);
        $this->eyebrowType = array_rand(self::EYEBROW_TYPE);
        $this->facialHairColor = array_rand(self::FACIAL_HAIR_COLOR);
        $this->facialHairType = array_rand(self::FACIAL_HAIR_TYPE);
        $this->graphicType = array_rand(self::GRAPHIC_TYPE);
        $this->hairColor = array_rand(self::HAT_COLOR);
        $this->hatColor = array_rand(self::HAT_COLOR);
        $this->mouthType = array_rand(self::MOUTH_TYPE);
        $this->skinColor = array_rand(self::SKIN_COLOR);
        $this->topType = array_rand(self::TOP_TYPE);
        $this->avatarStyle = 'transparent';
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
