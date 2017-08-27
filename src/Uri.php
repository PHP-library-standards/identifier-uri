<?php
/**
 * @package   Pls\Identifier\Uri
 * @author    PHP Library Standards <https://github.com/PHP-library-standards>
 * @copyright 2017 PHP Library Standards
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Pls\Identifier\Uri;

/**
 * An RFC 3986 URI.
 *
 * Instances of this interface MUST be immutable; all methods that might change
 * state MUST be implemented such that they retain the internal state of the
 * current instance and return an instance that contains the changed state.
 */
interface Uri
{
    /**
     * Returns the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     *
     * @return string The full URI or relative URI reference.
     */
    public function __toString(): string;

    /**
     * Gets the authority component.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is: [user-info@]host[:port]
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority(): string;

    /**
     * Gets the fragment component.
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters.
     *
     * @return string The percent-encoded URI fragment.
     */
    public function getFragment(): string;

    /**
     * Gets the host component.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase.
     *
     * @return string The lowercase normalized URI host.
     */
    public function getHost(): string;

    /**
     * Gets the path component.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * Normally, the empty path "" and absolute path "/" are considered equal.
     * But this method MUST NOT automatically do this normalization because in
     * contexts with a trimmed base path, e.g. the front controller, this
     * difference becomes significant. It is the task of the user to handle
     * both "" and "/".
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters.
     *
     * @return string The percent-encoded URI path.
     */
    public function getPath(): string;

    /**
     * Gets the port component.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an `int` value. If the port is the
     * standard port used with the current scheme, this method SHOULD return
     * `null`.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * `null`.
     *
     * If no port is present, but a scheme is present, this method SHOULD
     * return `null`.
     *
     * @return null|int The URI port.
     */
    public function getPort(): ?int;

    /**
     * Gets the query component.
     *
     * If no query string is present, this method MUST return an empty string.
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters.
     *
     * @return string The percent-encoded URI query.
     */
    public function getQuery(): string;

    /**
     * Retrieve the scheme component.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @return string The lowercase normalized URI scheme.
     */
    public function getScheme(): string;

    /**
     * Retrieve the user information component.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo(): string;

    /**
     * Returns a URI with the given fragment component.
     *
     * Users MAY provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in
     * `getFragment()`.
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment component for the new instance.
     *
     * @return static A new instance with the given fragment component.
     */
    public function withFragment(string $fragment): Uri;

    /**
     * Returns a URI with the given host component.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The host component for the new instance.
     *
     * @throws UriException MUST be thrown if $host is not a valid host
     *     component.
     *
     * @return static A new instance with the given host component.
     */
    public function withHost(string $host): Uri;

    /**
     * Returns a URI with the given path component.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If an HTTP path is intended to be host-relative rather than
     * path-relative then it must begin with a slash ("/"). HTTP paths not
     * starting with a slash are assumed to be relative to some base path known
     * to the application or consumer.
     *
     * Users MAY provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in `getPath()`.
     *
     * @param string $path The path component for the new instance.
     *
     * @throws UriException MUST be thrown if $path is not a valid path
     *     component.
     *
     * @return static A new instance with the given path component.
     */
    public function withPath(string $path): Uri;

    /**
     * Returns a URI with the given port component.
     *
     * `null` provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port component for the new instance.
     *
     * @throws UriException MUST be thrown if $port is outside the established
     *     TCP and UDP port ranges.
     *
     * @return static A new instance with the given port component.
     */
    public function withPort(?int $port): Uri;

    /**
     * Returns a URI with the given query component.
     *
     * Users MAY provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in `getQuery()`.
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query component for the new instance.
     *
     * @throws UriException MUST be thrown if $query is not a valid query
     *      component.
     *
     * @return static A new instance with the given query string component.
     */
    public function withQuery(string $query): Uri;

    /**
     * Returns a URI with the given scheme component.
     *
     * Implementations MUST support the schemes "http" and "https" and MAY
     * support other schemes. Schemes MUST be supported case insensitively.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme component for the new instance.
     *
     * @throws UriException MUST be thrown if $scheme is not a valid scheme
     *     component or is not supported.
     *
     * @return static A new instance with the given scheme component.
     */
    public function withScheme(string $scheme): Uri;

    /**
     * Return a URI with the given user information.
     *
     * $password is optional, but the user information MUST include $user; an
     * empty string for $user is equivalent to removing user information.
     *
     * @param string      $user     The username for the new instance.
     * @param null|string $password The password for the new instance.
     *
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo(string $user, string $password = null): Uri;
}
