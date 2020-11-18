<?php

class AddressFinderField extends FormField {
	protected $data;
    protected $children;
    protected $database_fields;
    protected $hidden_fields;

    // Config variables
	private static $Key;
	private static $Secret;
    private static $Placeholder_text;

    // Specifies which fields to save into the database
    // database field => api reference
    private static $default_database_fields = array();


	/**
	 * @param DataObject $data The controlling dataobject
	 * @param string $title The title of the field
	 * @param array $databaseFields Various extra fields to store into
	 */
	public function __construct(DataObject $data, $name, $title = null, $databaseFields = array(), $hiddenFields = false) {
		$this->data = $data;
        $this->name = $name;
        $this->hidden_fields = $hiddenFields;

        $title = !empty($title) ? $title : 'Search An Address';

        // check if the config is set
        $key = Config::inst()->get('AddressFinderField', 'Key');
        $secret = Config::inst()->get('AddressFinderField', 'Secret');

        if($this->CheckKeys()){
    		$this->setupFields($databaseFields);
    		parent::__construct($this->getName(), $title);
        }

        return;
	}

    public function CheckKeys(){
        // check if the config is set
        $key = Config::inst()->get('AddressFinderField', 'Key');
        $secret = Config::inst()->get('AddressFinderField', 'Secret');

        if(isset($key) && isset($secret)){
            return true;
        }
        return false;
    }

	// Auto generate a name
	public function getName() {
        $name = empty($this->name) ? 'addresFinderField' : $this->name;
        return $name;
	}

	/**
	 * Set up child fields
	 */
	public function setupFields($databaseFields) {
        $name = $this->getName();

        // Check if the passed one is empty
        if(empty($databaseFields)){
            $this->database_fields = Config::inst()->get('AddressFinderField', 'default_database_fields');
        } else {
            $this->database_fields = $databaseFields;
        }

        // Set the address finder field
        $this->children = new FieldList(
            TextField::create($name, '')
                ->setAttribute('placeholder', Config::inst()->get('AddressFinderField', 'Placeholder_text'))
                ->setAttribute('key', Config::inst()->get('AddressFinderField', 'Key'))
                ->addExtraClass('address-finder-field')
        );

        // Add all data fields
        foreach($this->database_fields as $db => $meta){
            // Adding via array naming allows for data extraction at the setting value stage
            if($this->hidden_fields){
                $field = HiddenField::create($this->fullChildFieldName($db), $this->data->fieldLabel($db), $this->recordFieldData($db));
            } else {
                $field = TextField::create($this->fullChildFieldName($db), $this->data->fieldLabel($db), $this->recordFieldData($db));
            }

            $field->addExtraClass('addressfinderfield-metafield')->setAttribute('metatype', $meta);

            $this->children->push($field);
        }

		return $this->children;
	}

	/**
	 * @param array $properties
	 * {@inheritdoc}
	 */
	public function Field($properties = array()) {
		$this->requireDependencies();

        $this->customise(array(
            'HiddenFields' => $this->hidden_fields
        ));
        
		foreach($this->getAttributes() as $k => $v) {
			if (!$this->children[0]->getAttribute($k))$this->children[0]->setAttribute($k,$v);
		}

		return parent::Field($properties);
	}

	/**
	 * Set up and include any frontend requirements
	 * @return void
	 */
	protected function requireDependencies() {
        if($this->CheckKeys()){
            // Reset for the first field
            //reset($this->database_fields);
            $fieldID = $this->getName(); // key($this->database_fields);

            $vars = array(
                'Key' => Config::inst()->get('AddressFinderField', 'Key'),
                'AddressFieldID' => $fieldID
            );

            // CSS
    		Requirements::css(ADDRESSFINDERFIELD_BASE .'/css/AddressFinderField.css');
            // Javascript
            if(!$this->isCMS()){
                Requirements::javascript(FRAMEWORK_DIR . '/thirdparty/jquery/jquery.min.js');
                Requirements::javascript(FRAMEWORK_DIR . '/thirdparty/jquery-entwine/dist/jquery.entwine-dist.js');
            }
    		//Requirements::javascriptTemplate(ADDRESSFINDERFIELD_BASE .'/javascript/AddressFinderField.js', $vars);
            Requirements::javascript(ADDRESSFINDERFIELD_BASE .'/javascript/AddressFinderField.js');
        }
	}

	/**
	 * {@inheritdoc}
	 */
	public function setValue($record) {
        // Check for defined databasefields
        if(!empty($this->database_fields)){
            $dbFields = $this->database_fields;
        } else {
            $dbFields = Config::inst()->get('AddressFinderField', 'default_database_fields');
        }
        
		// If fields are still empty then load into main field
		if (empty($dbFields)) {
			$this->value = $record;
			$this->getChildFields()->fieldByName($this->getName())->setValue($record);
		}

        // Loop fields and save the info
        foreach($dbFields as $db => $meta){
            $fieldName = $this->fullChildFieldName($db);
            $field = $this->getChildFields()->fieldByName($fieldName);
            $field->setValue(
    			$record[$db]
    		);
        }

		return $this;
	}

	/**
     *  Take the fields and save them to the DataObject.
     *  {@inheritdoc}
	 */
	public function saveInto(DataObjectInterface $record) {
        // Check for defined databasefields
        if(!empty($this->database_fields)){
            $dbFields = $this->database_fields;
        } else {
            $dbFields = Config::inst()->get('AddressFinderField', 'default_database_fields');
        }
        
		// If fields are still empty then load into main field
		if (empty($dbFields)) {
			$this->value = $record;
			$field = $this->getChildFields()->fieldByName($this->getName());
			$record->setCastedField($this->getName(), $field->dataValue());
		}

        // Loop fields and save the info
        foreach($dbFields as $db => $meta){
            $fieldName = $this->fullChildFieldName($db);
            $field = $this->getChildFields()->fieldByName($fieldName);
            $record->setCastedField($db, $field->dataValue());
        }

        return $this;
	}

	/**
	 * @return FieldList The Latitude/Longitude fields
	 */
	public function getChildFields() {
		return $this->children;
	}

    protected function fullChildFieldName($name){
        return $this->getName() . '[' . $name . ']';
    }

	protected function recordFieldData($name) {
		$fieldName = $name;
		return $this->data->$fieldName ?: $this->getDefaultValue($name);
	}

	public function getDefaultValue($name) {
        return null;
    }

    public function isCMS() {
        return Controller::curr() instanceof LeftAndMain;
    }
}
