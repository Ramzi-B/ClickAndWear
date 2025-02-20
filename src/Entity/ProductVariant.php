<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProductVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProductVariant
{
    use TimestampableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 60, unique: true)]
    private ?string $sku = null;

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Size $size = null;

    /**
     * @var Collection<int, Color>
     */
    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'variants')]
    private Collection $colors;

    /**
     * @var Collection<int, Material>
     */
    #[ORM\ManyToMany(targetEntity: Material::class, inversedBy: 'variants')]
    private Collection $materials;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
        $this->materials = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->sku ??= $this->generateSku();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        // Regenerate the SKU only if the size, product or colour changes
        if ($this->product || $this->size || !$this->sku) {
            $this->sku = $this->generateSku();
        }
    }

    /**
     * Format the price of the product variant, from cent to euro.
     *
     * @return string The formatted price.
     */
    public function getFormattedPrice(): string
    {
        if ($this->price === null) { return '0,00 €'; }

        return number_format(((float) $this->price) / 100, 2, ',', ' ') . ' €';
    }
    
    /**
     * Generate a unique SKU for the product variant.
     *
     * @throws \LogicException If the product or size are not set.
     *
     * @return string
     */
    public function generateSku(): string
    {
        if (!$this->product || !$this->size) {
            throw new \LogicException('Product and size are required to generate an SKU.');
        }

        $productName = strtoupper(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9]/', '', $this->product->getName())));
        $brandCode = strtoupper(substr($this->product->getBrand()->getName(), 0, 3));
        $genderCode = strtoupper(substr($this->product->getGenderLabel(), 0, 1));
        $sizeCode = strtoupper($this->size->getName());
        $colorNames = implode('-', $this->colors->map(fn($color) => strtoupper($color->getName()))->toArray());

        return sprintf('%s-%s-%s-%s-%s', $productName, $brandCode, $colorNames, $sizeCode, $genderCode);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getSize(): ?Size
    {
        return $this->size;
    }

    public function setSize(?Size $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, Color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): static
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): static
    {
        $this->colors->removeElement($color);

        return $this;
    }

    /**
     * @return Collection<int, Material>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
        }

        return $this;
    }

    public function removeMaterial(Material $material): static
    {
        $this->materials->removeElement($material);

        return $this;
    }

    public function __toString(): string
    {
        return $this->sku ?? '' . ' - ' . $this->product->getName();
    }
}
