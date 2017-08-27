<?php
/**
 * @package   Pls\Identifier\Uri
 * @author    PHP Library Standards <https://github.com/PHP-library-standards>
 * @copyright 2017 PHP Library Standards
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Pls\Identifier\Uri;

/**
 * Interface describing a URI factory.
 */
interface UriFactory
{
    /**
     * Creates a new URI.
     *
     * @param string $uri Optional. URI string to be parsed to create the
     *     instance from.
     *
     * @throws UriException MUST be thrown if $uri cannot be parsed.
     *
     * @return Uri The new URI instance.
     */
    public function createUri(string $uri = ''): Uri;
}
