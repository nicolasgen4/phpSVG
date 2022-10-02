<?php
/**
 * permet de manipuler l'entete d'un SVG et de l'afficher
 * @author David LEGRAND <wawawaformation@gmail.com>
 * @license GPL
 */
class EnteteSVG{
    /**
     * @var SimpleXMLElement $svg : le svg sous forme d'un arbre XML
     */
    private $svg;
    /**
     * @var StdObject $attributs : objet contenant les attributs de la balise svg
     */
    private $attributs;
    /**
     * @var string $contenu : contenu à l'interieur du noeud svg
     */
    private $contenu;
   
   
    

    /**
     * verifier : permet de verifier que le fichier existe et qu'il s'agit bien d'un SVG (sinon le script s'arrete)
     *
     * @param string $svg
     * @return void
     */
    private static function verifier(string $svg)
    {
       
        if(!file_exists($svg)){
            die('Ressource non trouvée: ' . $svg);
            
        } 
        
        if(strpos(mime_content_type($svg),'image/svg')){
            die('Ce n\'est pas un SVG: ' . $svg );
        
        }
      
    }

    /**
     * retournerAttributs : retourne les/un attribut(s) de la balise <svg>
     *
     * @param string|null $attribut : un attribut en particulier si non null
     * @return array les attributs
     */
    private function retournerAttributs(string $attribut = null){
        return (is_null($attribut)) ? get_object_vars($this->attributs) : $this->attributs->$attribut;
      
     }
     /**
      * definirAttribut : definir un attribut de la balise <svg> en ecrasant l'attribut si il existe deja
      *
      * @param string $attribut
      * @param string $valeur
      * @return void
      */
    private function definirAttribut(string $attribut, string $valeur){
        $this->attributs->$attribut = $valeur;
     }
     
    /**
     * supprimerAttribut : supprime un attribut de la balise <svg> si il existe
     *
     * @param string $attribut 
     * @return void
     */
    private function supprimerAttribut(string $attribut){
        if(!is_null($this->attributs->$attribut)) unset($this->attributs->$attribut);
    }


 
    


    /*======================================================================
                                 API
    =====================================================================*/
    
    
    /**
     * __construct : instancie la class EnteteSVG
     *
     * @param string $svg : fichier svg
     */
    public function __construct(string $svg)
    { 
        $svg = self::retournerDirectement($svg);

        //les attributs de la balise svg sont stockés dans $this-attributes 
        $this->svg = new SimpleXMLElement($svg);
        foreach($this->svg->attributes() as $clef=>$valeur){     
            $attributs[$clef] = $valeur->__toString();
        }
        $this->attributs = (object) $attributs;



        //On recupere le contenu du svg dans un type text (à venir : on parse avec SimpleXMLElement)
        $svg = str_ireplace('</svg>','',$svg);

        $i = 0;
        $sous_chaine='';
        while(true){
            $c = $svg[$i];
            $sous_chaine .= $c;
            if($c == '>') break;
            $i++;
        }
        $this->contenu = str_ireplace($sous_chaine,'',$svg);
        
      
      
    }


    /**
     * retournerDirectement : retourne le contenu du fichier SVG sans modif
     *
     * @param string $svg : fichier svg
     * @return string $svg : le contenu du fichier svg
     */
    public static function retournerDirectement(string $svg){
        self::verifier($svg);
        return file_get_contents($svg);
    }

    /**
     * nettoyerEntete : enleve tous les attributs (sauf le viewBox) de la balise <svg>
     *
     * @return void
     */
    public function nettoyerEntete(){
        foreach($this->retournerAttributs() as $clef=>$valeur){
            if($clef != 'viewBox') unset($this->attributs->$clef);    
        }
        
    }

    /**
     * definirUneClasse : definit l'attribut classe de la balise <svg> (ecrase les classes existantes)
     *
     * @param string $classe : valeur de la classe
     * @return void
     */
    public function definirUneClasse(string $classe){
        $this->definirAttribut('class', $classe);
    }


    /**
     * definirUneClasse : ajoute une classe  aux classes existantes pour la balise <svg> 
     *
     * @param string $classe : valeur de la classe à ajouter
     * @return void
     */
    public function ajouterUneClasse(string $classe){
        $this->definirAttribut('class', $this->retournerAttributs('class') . ' ' . $classe);
    }


    /**
     * definirUnId : definit un id pour la balise <svg>
     *
     * @param string $id : valeur de l'id
     * @return void
     */
    public function definirUnId(string $id){
        $this->definirAttribut('id',$id);
    }


    /**
     * definirUneCouleur : definit une couleur pour la balise <svg>
     *
     * @param string $couleur : la couleur (type HTML)
     * @return void
     */
    public function definirUneCouleur(string $couleur){
        //il faudra mettre un garde-fou pour $couleur
        $this->definirAttribut('fill', $couleur);
    }


    /**
     * redimensionner : ajoute l'attribut height et width à la balise <svg>
     *
     * @param string $largeur
     * @param string $hauteur
     * @return void
     */
    public function redimensionner(string $largeur, string $hauteur){
        if(!is_null($hauteur)) $this->definirAttribut('height', $hauteur);
        if(!is_null($hauteur)) $this->definirAttribut('width', $largeur);
    }

    
    /**
     * rendre : retourne le contenu du svg
     *
     * @return void
     */
    public function rendre(){
        $svg = '<svg' ;
        foreach($this->retournerAttributs() as $clef=>$valeur){
            $svg .= ' ' . $clef . '="' . $valeur . '"';
        }
        $svg .= ' xmlns="http://www.w3.org/2000/svg">';
        $svg .=$this->contenu;
        $svg .= '</svg>';

        return $svg;
       
    }

    
    /**
     * Enregistrer : enregistre le svg dans un  fichier 
     *
     * @param string $path
     * @return void
     */
    public function enregistrer(string $path){
        //il faudra verifier le path (le chemin existe?, on a les droits? etc)
        $svg = $this->rendre();
        file_put_contents($path, $svg);
    }
}