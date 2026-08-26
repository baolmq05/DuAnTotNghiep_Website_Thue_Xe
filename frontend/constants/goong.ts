/**
 * Cấu hình tập trung cho Goong Map (REST API & Map Tiles)
 * Khi cần thay đổi API Key hoặc Map Key, chỉ cần cập nhật tại file này.
 */
export const GOONG_CONFIG = {
  // REST API Key (dùng cho AutoComplete, Place Detail, Geocode, DistanceMatrix, Direction)
  API_KEY: 'xEcFmnV3loWHnfqa9ZsEENH7Wu6lehK4QmabQk7V',

  // Map Key (dùng để load tiles style hiển thị bản đồ)
  MAP_KEY: '8Gh3kHiOvTsc6QHzNT4Aq0aFjH2I69PNiFyzk5Ex',

  // URL Tiles style bản đồ
  getMapStyleUrl: (mapKey?: string) =>
    `https://tiles.goong.io/assets/goong_map_web.json?api_key=${mapKey || GOONG_CONFIG.MAP_KEY}`,

  // Các Endpoints chính của Goong REST API
  ENDPOINTS: {
    GEOCODE: 'https://rsapi.goong.io/Geocode',
    AUTOCOMPLETE: 'https://rsapi.goong.io/Place/AutoComplete',
    PLACE_DETAIL: 'https://rsapi.goong.io/Place/Detail',
    DISTANCE_MATRIX: 'https://rsapi.goong.io/DistanceMatrix',
    DIRECTION: 'https://rsapi.goong.io/Direction',
  }
} as const;

export const GOONG_API_KEY = GOONG_CONFIG.API_KEY;
export const GOONG_MAP_KEY = GOONG_CONFIG.MAP_KEY;
