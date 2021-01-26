# Silverstripe-Pardot
Pardot integration for SilverStripe

[![Latest Stable Version](https://poser.pugx.org/cyber-duck/silverstripe-pardot/v/stable)](https://packagist.org/packages/cyber-duck/silverstripe-pardot)
[![Latest Unstable Version](https://poser.pugx.org/cyber-duck/silverstripe-pardot/v/unstable)](https://packagist.org/packages/cyber-duck/silverstripe-pardot)
[![Total Downloads](https://poser.pugx.org/cyber-duck/silverstripe-pardot/downloads)](https://packagist.org/packages/cyber-duck/silverstripe-pardot)
[![License](https://poser.pugx.org/cyber-duck/silverstripe-pardot/license)](https://packagist.org/packages/cyber-duck/silverstripe-pardot)

Author: [Andrew Mc Cormack](https://github.com/Andrew-Mc-Cormack)

## Features

A SilverStripe module to add Pardot forms and dynamic content to pages

### SilverStripe 4 installation

Add the following to your composer.json file and run /dev/build?flush=all

```json
{  
    "require": {  
        "cyber-duck/silverstripe-pardot": "5.0.*"
    }
}
```

Add the required pardot authentication details to your .env file

```
PARDOT_EMAIL="youremail@..."
PARDOT_PASSWORD="1234567890$"
PARDOT_API_VERSION="3"
PARDOT_BUSINESS_UNIT_ID = "1234567890$"
PARDOT_CONSUMER_KEY = "1234567890$"
PARDOT_CONSUMER_SECRET = "1234567890$"
```

You can use version 2 or 3 of the Pardot API. This will depend on your Pardot account.