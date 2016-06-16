# The Movie Database API Wrapper

Partial implementation of wrapper to the www.themoviedb.org API. Created for testing purposes only.

## Usage

Add files to your project and get [The Movie Database](https://www.themoviedb.org) API key.

Implement Ascendens\Tmdb\Http\ClientInterface:

```php
use Ascendens\Tmdb\Http\ClientInterface

class HttpClient implements ClientInterface
```

Make instance of API client:

```php
use Ascendens\Tmdb\Client;

$httpClient = new HttpClient();
$apiClient = new Client('yourApiKey', $httpClient);
```

Use power of magic properties to access existent modules:

```php
/**
 * @var \Ascendens\Tmdb\Module\Configuration $configuration
 */
$configuration = $apiClient->configuration;
```

**Note**: not all API methods currently implemented. See API client class @property-read annotations for full list.

## License

The code is distributed under the terms of the MIT license (see [LICENSE](https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE)).