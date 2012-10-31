<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MINSAL\IndicadoresBundle\Entity\TablaDatos
 *
 * @ORM\Table(name="origen_datos")
 * @ORM\Entity
 * @UniqueEntity(fields="sentenciaSql", message="La sentencia SQL ya fue utilizada")
 */
class OrigenDatos {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tabla_datos_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var string $sentenciaSql
     *
     * @ORM\Column(name="sentencia_sql", type="text", nullable=true)
     */
    private $sentenciaSql;

    /**
     * @var Conexion
     *
     * @ORM\ManyToOne(targetEntity="Conexion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_conexion", referencedColumnName="id")
     * })
     */
    private $idConexion;
    
    /**
     * @var string $archivoNombre
     *
     * @ORM\Column(name="archivo_nombre", type="string", length=100, nullable=true)
     */    
    protected $archivoNombre;
             
    public $file;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="OrigenDatos")
     * @ORM\JoinTable(name="fusiones",
     *      joinColumns={@ORM\JoinColumn(name="id_origen_dato", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_origen_dato_fusionado", referencedColumnName="id")}
     *      )
     **/
    private $fusiones;
    
    /**
     * @ORM\OneToMany(targetEntity="Campo", mappedBy="origenDato")
     */
    private $campos;

    public function __construct() {
        $this->fusiones = new \Doctrine\Common\Collections\ArrayCollection();        
        $this->campos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getAbsolutePath() {
        return null === $this->archivoNombre ? null : $this->getUploadRootDir() . '/' . $this->archivoNombre;
    }

    public function getWebPath() {
        return null === $this->archivoNombre ? null : $this->getUploadDir() . '/' . $this->archivoNombre;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
        //return $basepath . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/origen_datos';
    }

    public function upload($basepath) {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        if (null === $basepath) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the target filename to move to
        $this->file->move($this->getUploadRootDir($basepath), $this->file->getClientOriginalName());

        // set the path property to the filename where you'ved saved the file
        $this->setArchivoNombre($this->file->getClientOriginalName());

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TablaDatos
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return TablaDatos
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion() {
        return $this->descripcion;
    }
        
    
    /**
     * Set archivoNombre
     *
     * @param string $archivoNombre
     * @return OrigenDatos
     */
    public function setArchivoNombre($archivoNombre)
    {
        $this->archivoNombre = $archivoNombre;
    
        return $this;
    }

    /**
     * Get archivoNombre
     *
     * @return string 
     */
    public function getArchivoNombre()
    {
        return $this->archivoNombre;
    }

    /**
     * Set sentenciaSql
     *
     * @param string $sentenciaSql
     * @return OrigenDatos
     */
    public function setSentenciaSql($sentenciaSql)
    {
        $this->sentenciaSql = $sentenciaSql;
    
        return $this;
    }

    /**
     * Get sentenciaSql
     *
     * @return string 
     */
    public function getSentenciaSql()
    {
        return $this->sentenciaSql;
    }

    /**
     * Set idConexion
     *
     * @param MINSAL\IndicadoresBundle\Entity\Conexion $idConexion
     * @return OrigenDatos
     */
    public function setIdConexion(\MINSAL\IndicadoresBundle\Entity\Conexion $idConexion = null)
    {
        $this->idConexion = $idConexion;
    
        return $this;
    }

    /**
     * Get idConexion
     *
     * @return MINSAL\IndicadoresBundle\Entity\Conexion 
     */
    public function getIdConexion()
    {
        return $this->idConexion;
    }

    
    
    public function __toString() {
        return $this->nombre;
    }

        

    /**
     * Add fusiones
     *
     * @param MINSAL\IndicadoresBundle\Entity\OrigenDatos $fusiones
     * @return OrigenDatos
     */
    public function addFusione(\MINSAL\IndicadoresBundle\Entity\OrigenDatos $fusiones)
    {
        $this->fusiones[] = $fusiones;
    
        return $this;
    }

    /**
     * Remove fusiones
     *
     * @param MINSAL\IndicadoresBundle\Entity\OrigenDatos $fusiones
     */
    public function removeFusione(\MINSAL\IndicadoresBundle\Entity\OrigenDatos $fusiones)
    {
        $this->fusiones->removeElement($fusiones);
    }

    /**
     * Get fusiones
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFusiones()
    {
        return $this->fusiones;
    }

    /**
     * Add campos
     *
     * @param MINSAL\IndicadoresBundle\Entity\Campo $campos
     * @return OrigenDatos
     */
    public function addCampo(\MINSAL\IndicadoresBundle\Entity\Campo $campos)
    {
        $this->campos[] = $campos;
    
        return $this;
    }

    /**
     * Remove campos
     *
     * @param MINSAL\IndicadoresBundle\Entity\Campo $campos
     */
    public function removeCampo(\MINSAL\IndicadoresBundle\Entity\Campo $campos)
    {
        $this->campos->removeElement($campos);
    }

    /**
     * Get campos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCampos()
    {
        return $this->campos;
    }
}