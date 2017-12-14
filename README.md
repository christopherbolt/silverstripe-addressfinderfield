# DEVELOPMENT - SilverStripe AddressFinderNZ Field #
A custom field for integrating the AddressFinderNZ API as both a backend and frontend field.
```
AddressFinderField::create(DataObject $data, $name, $title = null, $databaseFields = array(), $hiddenFields = false)
```
By default the field will pull the full address from the API and will save it into the database field 'Address'.

Alternatively you can specifiy multiple fields from the API to be saved to the database. You can do this by passing in the alternative database fields array as the 3rd variable of the field constructor.
The key of the array is the database field and the value should match the meta data returned by the API.
https://addressfinder.nz/docs/address_metadata_api/

### Requirements ###
This installer requires you have the following:
- Composer

### Configuration ###
1. Add your AddressFinderNZ API information to the config.yml:
```
AddressFinderField:
  Key: 'key-here'
  Secret: 'secret-here'
```
### Usage ###
1. Add the database fields to your page or object
```
private static $db = array(
        'Address' => 'Text'
);
```
2. Add the field to your fieldset
```
AddressFinderField::create($this, 'AddressFinder', 'Search address')
```
### Advanced Usage ###
1. Add the multiple database fields to your page or object
```
private static $db = array(
  'Address' => 'Text',
  'City' => 'Varchar',
  'Street' => 'Varchar'
);
```
2. Specify multiple address fields to save to your object
NOTE: The key of the array is the database field and the value should match the meta data returned by the API.
https://addressfinder.nz/docs/address_metadata_api/
```
$addressConfig = array(
  'Address' => 'a',
  'City' => 'city',
  'Street' => 'street'
);
```
3. Add the field to your fieldset
```
AddressFinderField::create($this, 'AddressFinder', 'Search address', $addressConfig)
```
### Frontend Usage ###
1. Add the address field but don't forget to specify the DataObject
```
AddressFinderField::create($this->data(), 'AddressFinder', 'Address Finder', $addressConfig, false)
```
