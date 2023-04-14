#define FFI_LIB "libbcos-c-sdk.so"
#define FFI_SCOPE "libFisco"

void* bcos_sdk_create_keypair(int crypto_type);
void bcos_sdk_destroy_keypair(void* key_pair);
void bcos_sdk_create_signed_transaction(void* key_pair, const char* group_id, const char* chain_id, const char* to, const char* data, const char* abi, int64_t block_limit, int32_t attribute, char** tx_hash, char** signed_tx);
const char* bcos_sdk_get_keypair_address(void* key_pair);
void* bcos_sdk_create_keypair_by_hex_private_key(int crypto_type, const char* private_key);
const char* bcos_sdk_get_keypair_private_key(void* key_pair);
void* bcos_sdk_create_keypair_by_hex_private_key(int crypto_type, const char* private_key);
void bcos_sdk_destroy_keypair(void* key_pair);
void bcos_sdk_c_free(void* p);