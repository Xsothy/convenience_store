/**
 * Address route configuration utility
 * Provides route configuration based on authentication status
 */

export interface AddressRouteConfig {
  store: string;
  update: string;
  delete: string;
  setDefault: string;
}

/**
 * Get address route configuration based on authentication status
 * @param isAuthenticated Whether the user is authenticated
 * @returns Route configuration for address operations
 */
export function getAddressRoutes(isAuthenticated: boolean): AddressRouteConfig {
  return isAuthenticated
    ? {
        // Routes for authenticated users
        store: 'account.addresses.store',
        update: 'account.addresses.update',
        delete: 'account.addresses.delete',
        setDefault: 'account.addresses.default',
      }
    : {
        // Routes for unauthenticated users (cart routes)
        store: 'cart.addresses.store',
        update: 'cart.addresses.update',
        delete: 'cart.addresses.delete',
        setDefault: 'cart.addresses.default',
      };
}

/**
 * Access route configuration using this object to avoid imports
 */
export const addressRoutes = {
  authenticated: getAddressRoutes(true),
  unauthenticated: getAddressRoutes(false),
}; 