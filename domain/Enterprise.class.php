<?phpclass Enterprise {        // Attributes    private $idEnterprise;    private $name;    private $mail;    private $password;    private $panel;    private $postalAddress;    private $postalCode;    private $city;    private $vat;    private $oneTypeOfProvider; // Class TypeOfProvider    private $oneProfile; // Class Profile    private $oneDepartment; // Class Department    private $dateDeletion;    private $positionStoreListStore;    private $membership;        // Constructor    public function __construct($name, $mail, $password, $panel, $postalAddress, $postalCode, $city, $vat, $oneTypeOfProvider, $oneProfile, $oneDepartment, $dateDeletion="", $idEnterprise = -1, $membership) {        $this->idEnterprise = $idEnterprise;        $this->name = $name;        $this->mail = $mail;        $this->password = $password;        $this->panel = $panel;        $this->postalAddress = $postalAddress;        $this->postalCode = $postalCode;        $this->city = $city;        $this->vat = $vat;        $this->oneTypeOfProvider = $oneTypeOfProvider;        $this->oneProfile = $oneProfile;        $this->oneDepartment = $oneDepartment;        $this->dateDeletion = $dateDeletion;        $this->positionStoreListStore = -1;        $this->membership = $membership;    }            // Getters & setters    public function getIdEnterprise() { return $this->idEnterprise; }    public function getName() { return $this->name; }    public function getMail() { return $this->mail; }        public function getPassword() { return $this->password; }         // Convert binary data into hexadecimal representation (because binary representation is not printable)    public function getPasswordHex() { return bin2hex($this->password); }        public function getPanel() { return $this->panel; }        public function getOneTypeOfProvider() { return $this->oneTypeOfProvider; }     public function getOneProfile() { return $this->oneProfile; }    public function getOneDepartment() { return $this->oneDepartment; }    public function getDateDeletion() { return $this->dateDeletion; }    public function getPositionStoreListStore() { return $this->positionStoreListStore; }        public function getPostalAddress() { return $this->postalAddress; }    public function getPostalCode() { return $this->postalCode; }    public function getCity() { return $this->city; }    public function getVat() { return $this->vat; }    public function getMembership() { return $this->vat; }    //SET    public function setIdEnterprise($idEnterprise) { $this->idEnterprise = $idEnterprise; }    public function setName($name) { $this->name = $name; }    public function setMail($mail) { $this->mail = $mail; }        public function setPassword($password) { $this->password = $password; }        public function setPanel($panel) { $this->panel = $panel; }    //on set le type de l'entreprise    public function setOneTypeOfProvider($oneTypeOfProvider) { $this->oneTypeOfProvider = $oneTypeOfProvider; }    public function setOneProfile($oneProfile) { $this->oneProfile = $oneProfile; }    public function setOneDepartment($oneDepartment) { $this->oneDepartment = $oneDepartment; }    public function setDateDeletion($dateDeletion) { $this->dateDeletion = $dateDeletion; }        public function setPositionStoreListStore($positionStoreListStore) { $this->positionStoreListStore = $positionStoreListStore; }        public function setPostalAddress($postalAddress) { $this->postalAddress = $postalAddress; }    public function setPostalCode($postalCode) { $this->postalCode = $postalCode; }    public function setCity($city) { $this->city = $city; }    public function setVat($vat) { $this->vat = $vat; }    public function setmembership($membership) { $this->membership = $membership; }}?>