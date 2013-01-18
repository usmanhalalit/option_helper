# Option Helper for CodeIgniter
This helper was made to mimic the tried-and-tested function of WordPress, the [Options API](http://codex.wordpress.org/Options_API).

## Functions
* _add_option(‘name_of_option','value')_ will store data in the db with ‘name_of_option' if the ‘name_of_option' is already used it will return false, use add_option if you want to store a data for the first time such as installing an app.
* _update_option(‘name_of_option','value')_ will update the 'name_of_option' if it is already used. If 'name_of_option' is not found it will add 'name_of_option'. So use update_option in general case.
* _get_option(‘name_of_option')_ will return the value of 'name_of_option' in original data type. Will return false if option is not found.
* _delete_option(‘name_of_option')_ will delete the option and return true/false on success/failure.

##Usage Example
```php
public function index()
{
    //load our helper,
    //better to autoload it by editing application/config/autoload.php
    $this->load->helper('option_helper');
 
    //text value example
    update_option('username','Usman');
    echo get_option('username');
    //array example
    $user_info=array(
        'username'      =>   'Usman',
        'profession'    =>   'Developer',
        'location'      =>   'Sylhet, Bangladesh',
    );
    update_option('user_info',$user_info);
    $return_value=get_option('user_info');
    print_r($return_value);
    echo $return_value['location'];
 
    //delete example
 
    delete_option('username');
    //delete_option('user_info');
}
```

## Implementation
1. Create the database table:

```
CREATE TABLE IF NOT EXISTS `tbl_option` (
 
`option_id` bigint(20) NOT NULL AUTO_INCREMENT,
 
`option_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
 
`option_value` longtext COLLATE utf8_unicode_ci NOT NULL,
 
`option_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
 
PRIMARY KEY (`option_id`),
 
UNIQUE KEY `option_name` (`option_name`)
 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=59 ;
```

2. Place option_helper.php in application/helpers folder (in most cases). I haven’t used any model for simplicity.