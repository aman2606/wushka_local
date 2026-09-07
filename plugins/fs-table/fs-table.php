<?php /**
 * @package             [FS] Table Pro element for YOOtheme Pro
 * @subpackage          fs-table
 *
 * @author              Flart Studio https://flart.studio
 * @copyright           Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license             GNU General Public License version 2 or later; see https://flart.studio/license
 * @link                https://flart.studio/yootheme-pro/table-pro
 * @build               (FLART_BUILD_NUMBER)
 *
 * @wordpress-plugin
 * Plugin Name:         [FS] Table Pro element for YOOtheme Pro
 * Plugin URI:          https://flart.studio/yootheme-pro/table-pro
 * Description:         Enhance your data presentation.
 * Version:             1.6.1
 * Requires at least:   6.6
 * Requires PHP:        8.1
 * Requires YOOtheme:   4.2.0
 * Author:              Flart Studio
 * Author URI:          https://flart.studio
 * License:             GPL-2.0-or-later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         fs-table
 */

/** @noinspection AutoloadingIssuesInspection, DuplicatedCode, PhpUnusedParameterInspection, HtmlUnknownTarget, ForgottenDebugOutputInspection */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

use YOOtheme\Application;

final class FS_Table_Plugin {
	// =========================================================================
	// PLUGIN CONFIGURATION — Edit these values per plugin
	// =========================================================================

	private const CONFIG = [
		// Plugin identity (auto-detected from the folder if empty)
		'slug'          => 'fs-table',

		// Update server
		'update_xml'    => 'https://flart.studio/updates/wordpress/table-pro.xml',

		// Links (leave empty to hide)
		'demo_url'      => 'https://flart.studio/yootheme-pro/table-pro/demo',
		'changelog_url' => 'https://flart.studio/yootheme-pro/table-pro/changelog',
		'support_url'   => 'https://flart.studio/contacts',

		// Plugin display name (for admin notices)
		'display_name'  => 'Table Pro',
	];

	// =========================================================================
	// INTERNAL CONSTANTS — Do not edit
	// =========================================================================

	private const DLID_OPTION = 'flart_dlid_option';
	private const SETTINGS_PAGE = 'flart-studio-settings';
	private const SETTINGS_GROUP = 'flart_dlid_option_group';
	private const EXTRA_HEADERS = [ 'RequiresYOOtheme' => 'Requires YOOtheme' ];
	private const ALLOWED_UPDATE_HOSTS = [ 'flart.studio' ];
	private const CACHE_TTL_WITH_DLID = WEEK_IN_SECONDS;
	private const CACHE_TTL_NO_DLID = MONTH_IN_SECONDS;
	private const CACHE_TTL_NOT_FOUND = DAY_IN_SECONDS;
	private const CACHE_TTL_ERROR = HOUR_IN_SECONDS;
	private const NOTICE_TTL = MINUTE_IN_SECONDS;

	// =========================================================================
	// INSTANCE PROPERTIES
	// =========================================================================

	private static ?self $instance = null;
	private ?array $updateData = null;
	private ?array $pluginData = null;
	private readonly string $slug;
	private readonly string $pluginFile;
	private readonly string $pluginBasename;

	// =========================================================================
	// PUBLIC API
	// =========================================================================

	public static function getInstance(): self {
		return self::$instance ??= new self();
	}

	public static function version(): string {
		return self::getInstance()->getPluginData()['Version'] ?? '1.0.0';
	}

	public static function slug(): string {
		return self::getInstance()->slug;
	}

	public static function requiresPhp(): string {
		return self::getInstance()->getPluginData()['RequiresPHP'] ?? '8.1';
	}

	public static function requiresWp(): string {
		return self::getInstance()->getPluginData()['RequiresWP'] ?? '6.6';
	}

	public static function requiresYootheme(): string {
		return self::getInstance()->getPluginData()['RequiresYOOtheme'] ?? '4.0.0';
	}

	// =========================================================================
	// CONSTRUCTOR
	// =========================================================================

	private function __construct() {
		$this->pluginFile     = __FILE__;
		$this->pluginBasename = plugin_basename( $this->pluginFile );

		// Use WordPress normalized path for slug detection
		$autoSlug   = dirname( $this->pluginBasename );
		$configSlug = trim( self::CONFIG['slug'] );

		if ( $configSlug !== '' && $configSlug !== $autoSlug && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf(
				'[Flart Studio] Slug mismatch: configured "%s", folder "%s"',
				$configSlug,
				$autoSlug
			) );
		}

		$this->slug = $configSlug ?: $autoSlug;

		$this->registerHooks();
	}

	private function registerHooks(): void {
		register_activation_hook( $this->pluginFile, [ $this, 'activate' ] );
		register_deactivation_hook( $this->pluginFile, [ $this, 'deactivate' ] );

		add_action( 'after_setup_theme', [ $this, 'bootstrap' ], 5 );

		if ( is_admin() ) {
			$this->initAdmin();
		}
	}

	// =========================================================================
	// LIFECYCLE
	// =========================================================================

	public function activate(): void {
		// Initialize DLID option if needed
		if ( get_option( self::DLID_OPTION ) === false ) {
			add_option( self::DLID_OPTION, '', '', false );
		}

		// Disable obsolete plugins if configured
		$this->disableObsoletePlugins();
	}

	public function deactivate(): void {
		$this->clearCache();
	}

	/**
	 * Disable obsolete plugins defined in CONFIG['disable_plugins'].
	 * Only plugins with the 'fs-' prefix can be disabled for security.
	 * Supports both site-activated and network-activated plugins.
	 */
	private function disableObsoletePlugins(): void {
		// Add capability check only
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( is_multisite() && ! current_user_can( 'manage_network_plugins' ) ) {
			return;
		}

		if ( ! isset( self::CONFIG['disable_plugins'] ) || self::CONFIG['disable_plugins'] === '' ) {
			return;
		}

		$slugsToDisable  = array_map( 'trim', explode( ',', self::CONFIG['disable_plugins'] ) );
		$disabledPlugins = [];

		foreach ( $slugsToDisable as $slug ) {
			// Security: only allow disabling fs-* plugins
			if ( $slug === '' || ! str_starts_with( $slug, 'fs-' ) ) {
				continue;
			}

			// Skip self
			if ( $slug === $this->slug ) {
				continue;
			}

			$pluginBasename = $this->findPluginBasename( $slug );

			if ( $pluginBasename === null ) {
				continue;
			}

			// Check and deactivate the site-activated plugin
			if ( is_plugin_active( $pluginBasename ) ) {
				deactivate_plugins( $pluginBasename );
				$disabledPlugins[] = $slug;
			}

			// Check and deactivate the network-activated plugin (multisite)
			if ( is_multisite() && is_plugin_active_for_network( $pluginBasename ) ) {
				deactivate_plugins( $pluginBasename, false, true );
				$disabledPlugins[] = $slug . ' (network)';
			}
		}

		// Store disabled plugins for admin notice
		if ( ! empty( $disabledPlugins ) ) {
			$transientKey = $this->slug . '-disabled-plugins';

			// Use site transient for network-wide visibility
			if ( is_multisite() ) {
				set_site_transient( $transientKey, $disabledPlugins, self::NOTICE_TTL );
			} else {
				set_transient( $transientKey, $disabledPlugins, self::NOTICE_TTL );
			}
		}
	}

	/**
	 * Find the plugin basename from slug.
	 */
	private function findPluginBasename( string $slug ): ?string {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$pluginBasename = $slug . '/' . $slug . '.php';

		return isset( get_plugins()[ $pluginBasename ] ) ? $pluginBasename : null;
	}

	public function bootstrap(): void {
		if ( ! class_exists( Application::class, false ) ) {
			add_action( 'admin_notices', [ $this, 'displayYoothemeMissingNotice' ] );
			add_action( 'admin_init', [ $this, 'maybeDeactivate' ] );

			return;
		}

		$theme      = wp_get_theme( 'yootheme' );
		$minVersion = self::requiresYootheme();

		if ( ! $theme->exists() || ! version_compare( $theme->get( 'Version' ), $minVersion, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'displayYoothemeVersionNotice' ] );
			add_action( 'admin_init', [ $this, 'maybeDeactivate' ] );

			return;
		}

		$bootstrap = dirname( $this->pluginFile ) . '/modules/bootstrap.php';

		if ( is_file( $bootstrap ) ) {
			Application::getInstance()->load( $bootstrap );
		}
	}

	public function displayYoothemeMissingNotice(): void {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			sprintf(
			/* translators: %s: plugin name */
				esc_html__( '%s requires YOOtheme Pro to be installed and activated.', 'flart-studio' ),
				'<strong>' . esc_html( self::CONFIG['display_name'] ) . '</strong>'
			)
		);
	}

	public function displayYoothemeVersionNotice(): void {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			sprintf(
			/* translators: 1: plugin name, 2: YOOtheme version */
				esc_html__( '%1$s requires YOOtheme Pro %2$s or later. The plugin has been deactivated.', 'flart-studio' ),
				'<strong>' . esc_html( self::CONFIG['display_name'] ) . '</strong>',
				'<strong>' . esc_html( self::requiresYootheme() ) . '</strong>'
			)
		);
	}

	public function maybeDeactivate(): void {
		deactivate_plugins( $this->pluginBasename );
		if ( isset( $_GET['activate'] ) ) {
			wp_safe_redirect( remove_query_arg( 'activate' ) );
			exit;
		}
	}

	// =========================================================================
	// ADMIN INITIALIZATION
	// =========================================================================

	private function initAdmin(): void {
		// Register settings hooks - actual duplicate prevention happens inside the callbacks
		add_action( 'admin_menu', [ $this, 'registerSettingsPage' ], 5 );
		add_action( 'admin_init', [ $this, 'registerSettings' ], 5 );

		// Plugin-specific hooks (always register for each plugin)
		add_action( 'admin_init', [ $this, 'handleForceUpdateCheck' ] );
		add_action( 'admin_notices', [ $this, 'displayUpdateNotice' ] );
		add_action( 'admin_notices', [ $this, 'displayDisabledPluginsNotice' ] );

		// Multisite: also show notices in network admin
		if ( is_multisite() ) {
			add_action( 'network_admin_notices', [ $this, 'displayUpdateNotice' ] );
			add_action( 'network_admin_notices', [ $this, 'displayDisabledPluginsNotice' ] );
		}

		add_filter( 'plugin_action_links_' . $this->pluginBasename, [ $this, 'addActionLinks' ] );
		add_filter( 'plugin_row_meta', [ $this, 'addRowMeta' ], 10, 2 );
		add_filter( 'plugins_api', [ $this, 'pluginInfo' ], 20, 3 );
		add_filter( 'site_transient_update_plugins', [ $this, 'checkUpdate' ] );
		add_action( 'upgrader_process_complete', [ $this, 'purgeCache' ], 10, 2 );
	}

	// =========================================================================
	// SETTINGS PAGE
	// =========================================================================

	public function registerSettingsPage(): void {
		global $flart_menu_initialized;

		// Check at execution time - covers all multisite scenarios
		if ( ! empty( $flart_menu_initialized ) || class_exists( 'FlartSettingsPage' ) ) {
			return;
		}

		$flart_menu_initialized = true;

		add_options_page(
			__( 'Flart Studio Settings', 'flart-studio' ),
			__( 'Flart Studio', 'flart-studio' ),
			'manage_options',
			self::SETTINGS_PAGE,
			[ $this, 'renderSettingsPage' ]
		);
	}

	public function registerSettings(): void {
		global $flart_settings_initialized;

		// Check at execution time - covers all multisite scenarios
		if ( ! empty( $flart_settings_initialized ) || class_exists( 'FlartSettingsPage' ) ) {
			return;
		}

		$flart_settings_initialized = true;

		// Ensure option exists
		if ( get_option( self::DLID_OPTION ) === false ) {
			add_option( self::DLID_OPTION, '', '', false );
		}

		register_setting( self::SETTINGS_GROUP, self::DLID_OPTION, [
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'sanitizeDlid' ],
			'default'           => '',
		] );

		add_settings_section(
			'flart_dlid_section',
			__( 'Download ID Settings', 'flart-studio' ),
			'__return_null',
			self::SETTINGS_PAGE
		);

		add_settings_field(
			self::DLID_OPTION,
			__( 'Download ID', 'flart-studio' ),
			[ $this, 'renderDlidField' ],
			self::SETTINGS_PAGE,
			'flart_dlid_section'
		);

		add_filter( 'pre_update_option_' . self::DLID_OPTION, [ $this, 'onDlidUpdate' ], 10, 2 );
	}

	public function renderSettingsPage(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
        <div class="wrap">
            <h1><?= esc_html( get_admin_page_title() ) ?></h1>
            <form method="post" action="options.php">
				<?php
				settings_fields( self::SETTINGS_GROUP );
				do_settings_sections( self::SETTINGS_PAGE );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	public function renderDlidField(): void {
		$dlid = get_option( self::DLID_OPTION, '' );

		?>
        <label for="<?= esc_attr( self::DLID_OPTION ) ?>"></label><input
                type="text"
                name="<?= esc_attr( self::DLID_OPTION ) ?>"
                id="<?= esc_attr( self::DLID_OPTION ) ?>"
                value="<?= esc_attr( $dlid ) ?>"
                placeholder="<?= esc_attr__( 'Enter Your Download ID', 'flart-studio' ) ?>"
                class="regular-text code"
                maxlength="40"
                autocomplete="off"
                spellcheck="false"
        />
        <p class="description">
            <a href="https://flart.studio/account/keys" target="_blank" rel="noopener noreferrer">
				<?= esc_html__( 'Get your Download ID', 'flart-studio' ) ?> ↗
            </a>
        </p>
		<?php
	}

	public function sanitizeDlid( mixed $input ): string {
		$sanitized = strtolower( sanitize_text_field( trim( (string) $input ) ) );

		if ( $sanitized === '' ) {
			return '';
		}

		// Validate an Akeeba Release System format:
		// - Simple: 32 lowercase hex characters
		// - With ID: digits: 32 lowercase hex characters
		if ( ! preg_match( '/^(\d+:)?[a-f0-9]{32}$/', $sanitized ) ) {
			add_settings_error(
				self::DLID_OPTION,
				'invalid_format',
				__( 'Invalid Download ID format.', 'flart-studio' ),
			);

			return get_option( self::DLID_OPTION, '' );
		}

		return $sanitized;
	}

	public function onDlidUpdate( mixed $newValue, mixed $oldValue ): mixed {
		if ( $newValue !== $oldValue ) {
			$this->clearAllFlartCaches();
		}

		return $newValue;
	}

	private function clearAllFlartCaches(): void {
		$plugins = array_merge(
			get_option( 'active_plugins', [] ),
			is_multisite() ? array_keys( get_site_option( 'active_sitewide_plugins', [] ) ) : []
		);

		foreach ( $plugins as $plugin ) {
			$slug = dirname( $plugin );

			if ( str_starts_with( $slug, 'fs-' ) ) {
				delete_transient( $slug . '-update-data' );
				delete_site_transient( $slug . '-update-data' );
			}
		}
	}

	/**
	 * Display admin notice when obsolete plugins were disabled.
	 */
	public function displayDisabledPluginsNotice(): void {
		$transientKey = $this->slug . '-disabled-plugins';

		$disabledPlugins = is_multisite()
			? get_site_transient( $transientKey )
			: get_transient( $transientKey );

		if ( empty( $disabledPlugins ) ) {
			return;
		}

		is_multisite()
			? delete_site_transient( $transientKey )
			: delete_transient( $transientKey );

		$pluginList = '<code>' . implode( '</code>, <code>', array_map( 'esc_html', $disabledPlugins ) ) . '</code>';

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			sprintf(
			/* translators: 1: plugin name, 2: list of disabled plugins */
				esc_html__( '%1$s has disabled the following obsolete plugins: %2$s', 'flart-studio' ),
				'<strong>' . esc_html( self::CONFIG['display_name'] ) . '</strong>',
				$pluginList
			)
		);
	}

	// =========================================================================
	// PLUGIN DATA
	// =========================================================================

	private function getPluginData(): array {
		if ( $this->pluginData === null ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$this->pluginData = get_plugin_data( $this->pluginFile, false, false );

			// Parse custom headers
			$fileContent = file_get_contents( $this->pluginFile, false, null, 0, 8192 );

			foreach ( self::EXTRA_HEADERS as $key => $header ) {
				if ( preg_match( '/^[\s*]*' . preg_quote( $header, '/' ) . ':\s*(.+)$/mi', $fileContent, $match ) ) {
					$this->pluginData[ $key ] = trim( $match[1] );
				}
			}
		}

		return $this->pluginData;
	}

	private function getDlid(): string {
		static $dlid = null;

		return $dlid ??= get_option( self::DLID_OPTION, '' );
	}

	/**
	 * Validate that update URL is from an allowed host.
	 */
	private function isValidUpdateUrl( string $url ): bool {
		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		$host = parse_url( $url, PHP_URL_HOST ) ?? '';

		foreach ( self::ALLOWED_UPDATE_HOSTS as $allowedHost ) {
			if ( $host === $allowedHost || str_ends_with( $host, '.' . $allowedHost ) ) {
				return true;
			}
		}

		return false;
	}

	// =========================================================================
	// UPDATE SYSTEM
	// =========================================================================

	private function getCacheKey(): string {
		return $this->slug . '-update-data';
	}

	private function getStatusKey(): string {
		return $this->slug . '-update-status';
	}

	private function getTransient( string $key ): mixed {
		return is_multisite() ? get_site_transient( $key ) : get_transient( $key );
	}

	private function setTransient( string $key, mixed $value, int $expiration ): void {
		is_multisite()
			? set_site_transient( $key, $value, $expiration )
			: set_transient( $key, $value, $expiration );
	}

	private function deleteTransient( string $key ): void {
		is_multisite() ? delete_site_transient( $key ) : delete_transient( $key );
	}

	private function clearCache(): void {
		$this->deleteTransient( $this->getCacheKey() );
		$this->deleteTransient( $this->getStatusKey() );
		$this->updateData = null;
	}

	/**
	 * Validate that the update URL points to an allowed domain.
	 */
	private function isAllowedUpdateHost( string $url ): bool {
		$host = parse_url( $url, PHP_URL_HOST );

		if ( $host === null || $host === false ) {
			return false;
		}

		// Check the exact match or subdomain match
		foreach ( self::ALLOWED_UPDATE_HOSTS as $allowedHost ) {
			if ( $host === $allowedHost || str_ends_with( $host, '.' . $allowedHost ) ) {
				return true;
			}
		}

		return false;
	}

	private function fetchUpdateData(): ?array {
		// Return cached data if available in memory
		if ( $this->updateData !== null ) {
			// Check if cached data indicates a "not found" or "error" state
			if ( ! empty( $this->updateData['_not_found'] ) || ! empty( $this->updateData['_error'] ) ) {
				return null;
			}

			return $this->updateData;
		}

		// Check transient cache
		$cached = $this->getTransient( $this->getCacheKey() );

		if ( is_array( $cached ) ) {
			$this->updateData = $cached;

			// Check if cached data indicates a "not found" or "error" state
			if ( ! empty( $cached['_not_found'] ) || ! empty( $cached['_error'] ) ) {
				return null;
			}

			if ( ! empty( $cached ) ) {
				return $cached;
			}
		}

		// Validate update URL before making a request
		$updateUrl = self::CONFIG['update_xml'];

		if ( empty( $updateUrl ) || ! filter_var( $updateUrl, FILTER_VALIDATE_URL ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf(
					'[Flart Studio] Invalid update URL for %s: %s',
					$this->slug,
					$updateUrl
				) );
			}

			// Cache the error state
			$this->updateData = [ '_error' => true, '_message' => 'Invalid URL' ];
			$this->setTransient( $this->getCacheKey(), $this->updateData, self::CACHE_TTL_ERROR );

			return null;
		}

		// Security: validate update URL domain
		if ( ! $this->isAllowedUpdateHost( $updateUrl ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf(
					'[Flart Studio] Blocked update request to untrusted host for %s: %s',
					$this->slug,
					$updateUrl
				) );
			}

			$this->updateData = [ '_error' => true, '_message' => 'Untrusted host' ];
			$this->setTransient( $this->getCacheKey(), $this->updateData, self::CACHE_TTL_ERROR );

			return null;
		}

		// Make HTTP request
		$response = wp_remote_get( $updateUrl, [
			'timeout'   => 15,
			'sslverify' => true,
			'headers'   => [
				'Accept'     => 'application/xml, text/xml',
				'User-Agent' => sprintf( 'FlartStudio/%s (WordPress; %s)', self::version(), home_url() )
			],
		] );

		if ( is_wp_error( $response ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf(
					'[Flart Studio] HTTP error for %s: %s',
					$this->slug,
					$response->get_error_message()
				) );
			}

			// Cache the error state to prevent repeated requests
			$this->updateData = [ '_error' => true, '_message' => $response->get_error_message() ];
			$this->setTransient( $this->getCacheKey(), $this->updateData, self::CACHE_TTL_ERROR );
			$this->setTransient( $this->getStatusKey(), 'error', self::NOTICE_TTL );

			return null;
		}

		$statusCode = wp_remote_retrieve_response_code( $response );

		if ( $statusCode !== 200 ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf(
					'[Flart Studio] HTTP %d for %s update check',
					$statusCode,
					$this->slug
				) );
			}

			// Cache the error state to prevent repeated requests
			$this->updateData = [ '_error' => true, '_message' => 'HTTP ' . $statusCode ];
			$this->setTransient( $this->getCacheKey(), $this->updateData, self::CACHE_TTL_ERROR );
			$this->setTransient( $this->getStatusKey(), 'error', self::NOTICE_TTL );

			return null;
		}

		$data = $this->parseUpdateXml( wp_remote_retrieve_body( $response ) );

		if ( $data === null ) {
			// Slug isn't found in XML — cache this state to prevent repeated requests
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf(
					'[Flart Studio] Slug "%s" not found in update XML: %s',
					$this->slug,
					$updateUrl
				) );
			}

			$this->updateData = [ '_not_found' => true, '_slug' => $this->slug ];
			$this->setTransient( $this->getCacheKey(), $this->updateData, self::CACHE_TTL_NOT_FOUND );

			return null;
		}

		// Cache successful response
		$ttl = $this->getDlid() !== '' ? self::CACHE_TTL_WITH_DLID : self::CACHE_TTL_NO_DLID;
		$this->setTransient( $this->getCacheKey(), $data, $ttl );
		$this->updateData = $data;

		return $data;
	}

	private function parseUpdateXml( string $body ): ?array {
		libxml_use_internal_errors( true );
		$xml = simplexml_load_string( $body, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA );

		if ( ! $xml instanceof SimpleXMLElement ) {
			$errors = libxml_get_errors();
			libxml_clear_errors();

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf(
					'[Flart Studio] XML parse error for %s: %s',
					$this->slug,
					$errors[0]->message ?? 'Unknown error'
				) );
			}

			return null;
		}

		libxml_clear_errors();

		$latestVersion = '0.0.0';
		$latestRelease = null;

		foreach ( $xml->update as $update ) {
			if ( (string) $update->element !== $this->slug ) {
				continue;
			}

			$version = (string) $update->version;

			if ( version_compare( $version, $latestVersion, '>' ) ) {
				$latestVersion = $version;
				$latestRelease = $update;
			}
		}

		// Slug isn't found in XML
		if ( $latestRelease === null ) {
			return null;
		}

		// Validate download URL
		$downloadUrl = (string) $latestRelease->downloads->downloadurl;
		if ( ! $this->isValidUpdateUrl( $downloadUrl ) ) {
			return null;
		}

		// Validate version format
		if ( ! preg_match( '/^\d+\.\d+(\.\d+)?$/D', $latestVersion ) ) {
			return null;
		}

		$dlid = $this->getDlid();

		if ( $dlid !== '' ) {
			$separator   = str_contains( $downloadUrl, '?' ) ? '&' : '?';
			$downloadUrl .= $separator . 'dlid=' . urlencode( $dlid );
		}

		return [
			'slug'         => $this->slug,
			'plugin'       => $this->pluginBasename,
			'name'         => (string) ( $latestRelease->name ?? '' ),
			'version'      => $latestVersion,
			'author'       => (string) ( $latestRelease->maintainer ?? '' ),
			'homepage'     => (string) ( $latestRelease->infourl ?? '' ),
			'download_url' => $downloadUrl,
			'changelog'    => (string) ( $latestRelease->changelog ?? '' ),
			'last_updated' => (string) ( $latestRelease->created ?? '' ),
			'requires_php' => (string) ( $latestRelease->php_minimum ?? self::requiresPhp() ),
			'tested'       => (string) ( $latestRelease->targetplatform['version'] ?? '' ),
		];
	}

	public function pluginInfo( mixed $result, string $action, object $args ): mixed {
		if ( $action !== 'plugin_information' || ( $args->slug ?? '' ) !== $this->slug ) {
			return $result;
		}

		$data = $this->fetchUpdateData();

		if ( $data === null ) {
			return $result;
		}

		return (object) [
			'name'          => $data['name'],
			'slug'          => $data['slug'],
			'version'       => $data['version'],
			'author'        => '<a href="' . esc_url( 'https://flart.studio' ) . '">' . esc_html( $data['author'] ) . '</a>',
			'homepage'      => $data['homepage'],
			'download_link' => $data['download_url'],
			'requires'      => self::requiresWp(),
			'requires_php'  => $data['requires_php'],
			'tested'        => $data['tested'] ?: get_bloginfo( 'version' ),
			'last_updated'  => $data['last_updated'],
			'sections'      => [
				'description' => sprintf(
					'<p>%s</p>',
					esc_html__( 'YOOtheme Pro element by Flart Studio.', 'flart-studio' )
				),
				'changelog'   => self::CONFIG['changelog_url']
					? sprintf(
						'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
						esc_url( self::CONFIG['changelog_url'] ),
						esc_html__( 'View full changelog on our website', 'flart-studio' )
					)
					: '',
			],
			'banners'       => [],
		];
	}

	public function checkUpdate( mixed $transient ): mixed {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		if ( empty( $transient->checked[ $this->pluginBasename ] ) ) {
			return $transient;
		}

		$data = $this->fetchUpdateData();

		if ( $data === null ) {
			return $transient;
		}

		if ( ! version_compare( self::version(), $data['version'], '<' ) ) {
			// No update available — add to a no_update list
			$transient->no_update[ $this->pluginBasename ] = (object) [
				'slug'        => $this->slug,
				'plugin'      => $this->pluginBasename,
				'new_version' => $data['version'],
			];

			return $transient;
		}

		$transient->response[ $this->pluginBasename ] = (object) [
			'slug'         => $this->slug,
			'plugin'       => $this->pluginBasename,
			'new_version'  => $data['version'],
			'package'      => $data['download_url'],
			'tested'       => $data['tested'] ?: get_bloginfo( 'version' ),
			'requires_php' => $data['requires_php'],
			'requires'     => self::requiresWp(),
		];

		return $transient;
	}

	public function handleForceUpdateCheck(): void {
		$param = $this->slug . '_force_check';

		if ( ! isset( $_GET[ $param ] ) ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'flart-studio' ) );
		}

		check_admin_referer( $param );

		$this->clearCache();
		$data = $this->fetchUpdateData();

		$status = match ( true ) {
			$data === null => 'error',
			version_compare( self::version(), $data['version'], '<' ) => 'update_available',
			default => 'no_update',
		};

		$this->setTransient( $this->getStatusKey(), $status, self::NOTICE_TTL );

		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit;
	}

	public function displayUpdateNotice(): void {
		$status = $this->getTransient( $this->getStatusKey() );

		if ( $status === false ) {
			return;
		}

		$this->deleteTransient( $this->getStatusKey() );

		$displayName = esc_html( self::CONFIG['display_name'] );

		[ $type, $message ] = match ( $status ) {
			'update_available' => [
				'success',
				sprintf(
				/* translators: %s: plugin name */
					__( 'New update available for %s.', 'flart-studio' ),
					'<strong>' . $displayName . '</strong>'
				),
			],
			'no_update' => [
				'info',
				sprintf(
				/* translators: %s: plugin name */
					__( '%s is up to date.', 'flart-studio' ),
					'<strong>' . $displayName . '</strong>'
				),
			],
			'error' => [
				'error',
				sprintf(
				/* translators: %s: support URL */
					__( 'Unable to check for updates. <a href="%s" target="_blank" rel="noopener noreferrer">Contact support</a> if the problem persists.',
						'flart-studio' ),
					esc_url( self::CONFIG['support_url'] )
				),
			],
			default => [ null, null ],
		};

		if ( $type !== null ) {
			printf(
				'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
				esc_attr( $type ),
				wp_kses_post( $message )
			);
		}
	}

	public function purgeCache( object $_upgrader, array $options ): void {
		if ( ( $options['action'] ?? '' ) !== 'update' ) {
			return;
		}

		if ( ( $options['type'] ?? '' ) !== 'plugin' ) {
			return;
		}

		$plugins = (array) ( $options['plugins'] ?? [] );

		if ( in_array( $this->pluginBasename, $plugins, true ) ) {
			$this->clearCache();
		}
	}

	// =========================================================================
	// PLUGIN LINKS
	// =========================================================================

	public function addActionLinks( array $links ): array {
		$settingsLink = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=' . self::SETTINGS_PAGE ) ),
			esc_html__( 'Settings', 'flart-studio' )
		);

		array_unshift( $links, $settingsLink );

		if ( $this->getDlid() !== '' ) {
			$checkUrl = wp_nonce_url(
				admin_url( 'plugins.php?' . $this->slug . '_force_check=1' ),
				$this->slug . '_force_check'
			);

			$links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $checkUrl ),
				esc_html__( 'Check for Update', 'flart-studio' )
			);
		}

		return $links;
	}

	public function addRowMeta( array $links, string $file ): array {
		if ( $file !== $this->pluginBasename ) {
			return $links;
		}

		if ( self::CONFIG['demo_url'] !== '' ) {
			$links['demo'] = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( self::CONFIG['demo_url'] ),
				esc_html__( 'Demo', 'flart-studio' )
			);
		}

		if ( self::CONFIG['changelog_url'] !== '' ) {
			$links['changelog'] = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( self::CONFIG['changelog_url'] ),
				esc_html__( 'Changelog', 'flart-studio' )
			);
		}

		return $links;
	}
}

FS_Table_Plugin::getInstance();