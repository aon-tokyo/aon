export interface LanguageEntry {
  code: string;
  googleCode: string;
  name: string;
  nativeName: string;
  flag: string;
  region: string;
}

/**
 * Comprehensive list of languages supported by Google Translate website translation.
 * `googleCode` handles irregular codes that Google Translate uses in the
 * translate.goog URL scheme (e.g. Hebrew uses "iw" not "he", Javanese uses "jw" not "jv").
 */
export const languages: LanguageEntry[] = [
  // --- Major / Featured ---
  { code: "en", googleCode: "en", name: "English", nativeName: "English", flag: "🇺🇸", region: "Americas" },
  { code: "en-GB", googleCode: "en", name: "English (UK)", nativeName: "English (UK)", flag: "🇬🇧", region: "Europe" },
  { code: "zh-CN", googleCode: "zh-CN", name: "Chinese (Simplified)", nativeName: "简体中文", flag: "🇨🇳", region: "Asia" },
  { code: "zh-TW", googleCode: "zh-TW", name: "Chinese (Traditional)", nativeName: "繁體中文", flag: "🇹🇼", region: "Asia" },
  { code: "es", googleCode: "es", name: "Spanish", nativeName: "Español", flag: "🇪🇸", region: "Europe" },
  { code: "fr", googleCode: "fr", name: "French", nativeName: "Français", flag: "🇫🇷", region: "Europe" },
  { code: "de", googleCode: "de", name: "German", nativeName: "Deutsch", flag: "🇩🇪", region: "Europe" },
  { code: "it", googleCode: "it", name: "Italian", nativeName: "Italiano", flag: "🇮🇹", region: "Europe" },
  { code: "pt", googleCode: "pt", name: "Portuguese", nativeName: "Português", flag: "🇵🇹", region: "Europe" },
  { code: "pt-BR", googleCode: "pt", name: "Portuguese (Brazil)", nativeName: "Português (Brasil)", flag: "🇧🇷", region: "Americas" },
  { code: "ru", googleCode: "ru", name: "Russian", nativeName: "Русский", flag: "🇷🇺", region: "Europe" },
  { code: "ko", googleCode: "ko", name: "Korean", nativeName: "한국어", flag: "🇰🇷", region: "Asia" },
  { code: "ar", googleCode: "ar", name: "Arabic", nativeName: "العربية", flag: "🇸🇦", region: "Middle East" },
  { code: "hi", googleCode: "hi", name: "Hindi", nativeName: "हिन्दी", flag: "🇮🇳", region: "Asia" },
  { code: "ja", googleCode: "ja", name: "Japanese", nativeName: "日本語", flag: "🇯🇵", region: "Asia" },

  // --- European ---
  { code: "nl", googleCode: "nl", name: "Dutch", nativeName: "Nederlands", flag: "🇳🇱", region: "Europe" },
  { code: "pl", googleCode: "pl", name: "Polish", nativeName: "Polski", flag: "🇵🇱", region: "Europe" },
  { code: "sv", googleCode: "sv", name: "Swedish", nativeName: "Svenska", flag: "🇸🇪", region: "Europe" },
  { code: "da", googleCode: "da", name: "Danish", nativeName: "Dansk", flag: "🇩🇰", region: "Europe" },
  { code: "fi", googleCode: "fi", name: "Finnish", nativeName: "Suomi", flag: "🇫🇮", region: "Europe" },
  { code: "no", googleCode: "no", name: "Norwegian", nativeName: "Norsk", flag: "🇳🇴", region: "Europe" },
  { code: "cs", googleCode: "cs", name: "Czech", nativeName: "Čeština", flag: "🇨🇿", region: "Europe" },
  { code: "sk", googleCode: "sk", name: "Slovak", nativeName: "Slovenčina", flag: "🇸🇰", region: "Europe" },
  { code: "hu", googleCode: "hu", name: "Hungarian", nativeName: "Magyar", flag: "🇭🇺", region: "Europe" },
  { code: "ro", googleCode: "ro", name: "Romanian", nativeName: "Română", flag: "🇷🇴", region: "Europe" },
  { code: "bg", googleCode: "bg", name: "Bulgarian", nativeName: "Български", flag: "🇧🇬", region: "Europe" },
  { code: "el", googleCode: "el", name: "Greek", nativeName: "Ελληνικά", flag: "🇬🇷", region: "Europe" },
  { code: "hr", googleCode: "hr", name: "Croatian", nativeName: "Hrvatski", flag: "🇭🇷", region: "Europe" },
  { code: "sr", googleCode: "sr", name: "Serbian", nativeName: "Српски", flag: "🇷🇸", region: "Europe" },
  { code: "sl", googleCode: "sl", name: "Slovenian", nativeName: "Slovenščina", flag: "🇸🇮", region: "Europe" },
  { code: "et", googleCode: "et", name: "Estonian", nativeName: "Eesti", flag: "🇪🇪", region: "Europe" },
  { code: "lv", googleCode: "lv", name: "Latvian", nativeName: "Latviešu", flag: "🇱🇻", region: "Europe" },
  { code: "lt", googleCode: "lt", name: "Lithuanian", nativeName: "Lietuvių", flag: "🇱🇹", region: "Europe" },
  { code: "uk", googleCode: "uk", name: "Ukrainian", nativeName: "Українська", flag: "🇺🇦", region: "Europe" },
  { code: "be", googleCode: "be", name: "Belarusian", nativeName: "Беларуская", flag: "🇧🇾", region: "Europe" },
  { code: "is", googleCode: "is", name: "Icelandic", nativeName: "Íslenska", flag: "🇮🇸", region: "Europe" },
  { code: "ga", googleCode: "ga", name: "Irish", nativeName: "Gaeilge", flag: "🇮🇪", region: "Europe" },
  { code: "cy", googleCode: "cy", name: "Welsh", nativeName: "Cymraeg", flag: "🏴󠁧󠁢󠁷󠁬󠁳󠁿", region: "Europe" },
  { code: "gd", googleCode: "gd", name: "Scots Gaelic", nativeName: "Gàidhlig", flag: "🏴󠁧󠁢󠁳󠁣󠁴󠁿", region: "Europe" },
  { code: "mt", googleCode: "mt", name: "Maltese", nativeName: "Malti", flag: "🇲🇹", region: "Europe" },
  { code: "sq", googleCode: "sq", name: "Albanian", nativeName: "Shqip", flag: "🇦🇱", region: "Europe" },
  { code: "mk", googleCode: "mk", name: "Macedonian", nativeName: "Македонски", flag: "🇲🇰", region: "Europe" },
  { code: "bs", googleCode: "bs", name: "Bosnian", nativeName: "Bosanski", flag: "🇧🇦", region: "Europe" },
  { code: "lb", googleCode: "lb", name: "Luxembourgish", nativeName: "Lëtzebuergesch", flag: "🇱🇺", region: "Europe" },
  { code: "ca", googleCode: "ca", name: "Catalan", nativeName: "Català", flag: "🇪🇸", region: "Europe" },
  { code: "gl", googleCode: "gl", name: "Galician", nativeName: "Galego", flag: "🇪🇸", region: "Europe" },
  { code: "eu", googleCode: "eu", name: "Basque", nativeName: "Euskara", flag: "🇪🇸", region: "Europe" },
  { code: "fy", googleCode: "fy", name: "Frisian", nativeName: "Frysk", flag: "🇳🇱", region: "Europe" },
  { code: "co", googleCode: "co", name: "Corsican", nativeName: "Corsu", flag: "🇫🇷", region: "Europe" },
  { code: "eo", googleCode: "eo", name: "Esperanto", nativeName: "Esperanto", flag: "🌍", region: "Europe" },
  { code: "la", googleCode: "la", name: "Latin", nativeName: "Latina", flag: "🏛️", region: "Europe" },
  { code: "oc", googleCode: "oc", name: "Occitan", nativeName: "Occitan", flag: "🇫🇷", region: "Europe" },
  { code: "br", googleCode: "br", name: "Breton", nativeName: "Brezhoneg", flag: "🇫🇷", region: "Europe" },
  { code: "ka", googleCode: "ka", name: "Georgian", nativeName: "ქართული", flag: "🇬🇪", region: "Europe" },
  { code: "hy", googleCode: "hy", name: "Armenian", nativeName: "Հայերեն", flag: "🇦🇲", region: "Europe" },
  { code: "az", googleCode: "az", name: "Azerbaijani", nativeName: "Azərbaycan", flag: "🇦🇿", region: "Europe" },
  { code: "tr", googleCode: "tr", name: "Turkish", nativeName: "Türkçe", flag: "🇹🇷", region: "Middle East" },

  // --- Middle East & Central Asia ---
  { code: "fa", googleCode: "fa", name: "Persian", nativeName: "فارسی", flag: "🇮🇷", region: "Middle East" },
  { code: "he", googleCode: "iw", name: "Hebrew", nativeName: "עברית", flag: "🇮🇱", region: "Middle East" },
  { code: "ur", googleCode: "ur", name: "Urdu", nativeName: "اردو", flag: "🇵🇰", region: "Middle East" },
  { code: "ps", googleCode: "ps", name: "Pashto", nativeName: "پښتو", flag: "🇦🇫", region: "Middle East" },
  { code: "ku", googleCode: "ku", name: "Kurdish (Kurmanji)", nativeName: "Kurdî", flag: "🇮🇶", region: "Middle East" },
  { code: "ckb", googleCode: "ckb", name: "Kurdish (Sorani)", nativeName: "سۆرانی", flag: "🇮🇶", region: "Middle East" },
  { code: "kk", googleCode: "kk", name: "Kazakh", nativeName: "Қазақ", flag: "🇰🇿", region: "Middle East" },
  { code: "ky", googleCode: "ky", name: "Kyrgyz", nativeName: "Кыргызча", flag: "🇰🇬", region: "Middle East" },
  { code: "uz", googleCode: "uz", name: "Uzbek", nativeName: "Oʻzbek", flag: "🇺🇿", region: "Middle East" },
  { code: "tg", googleCode: "tg", name: "Tajik", nativeName: "Тоҷикӣ", flag: "🇹🇯", region: "Middle East" },
  { code: "tk", googleCode: "tk", name: "Turkmen", nativeName: "Türkmen", flag: "🇹🇲", region: "Middle East" },
  { code: "mn", googleCode: "mn", name: "Mongolian", nativeName: "Монгол", flag: "🇲🇳", region: "Asia" },
  { code: "ug", googleCode: "ug", name: "Uyghur", nativeName: "ئۇيغۇرچە", flag: "🇨🇳", region: "Asia" },
  { code: "tt", googleCode: "tt", name: "Tatar", nativeName: "Татарча", flag: "🇷🇺", region: "Europe" },
  { code: "ba", googleCode: "ba", name: "Bashkir", nativeName: "Башҡорт", flag: "🇷🇺", region: "Europe" },
  { code: "cv", googleCode: "cv", name: "Chuvash", nativeName: "Чӑваш", flag: "🇷🇺", region: "Europe" },

  // --- South Asia ---
  { code: "bn", googleCode: "bn", name: "Bengali", nativeName: "বাংলা", flag: "🇧🇩", region: "Asia" },
  { code: "ta", googleCode: "ta", name: "Tamil", nativeName: "தமிழ்", flag: "🇮🇳", region: "Asia" },
  { code: "te", googleCode: "te", name: "Telugu", nativeName: "తెలుగు", flag: "🇮🇳", region: "Asia" },
  { code: "ml", googleCode: "ml", name: "Malayalam", nativeName: "മലയാളം", flag: "🇮🇳", region: "Asia" },
  { code: "kn", googleCode: "kn", name: "Kannada", nativeName: "ಕನ್ನಡ", flag: "🇮🇳", region: "Asia" },
  { code: "gu", googleCode: "gu", name: "Gujarati", nativeName: "ગુજરાતી", flag: "🇮🇳", region: "Asia" },
  { code: "mr", googleCode: "mr", name: "Marathi", nativeName: "मराठी", flag: "🇮🇳", region: "Asia" },
  { code: "pa", googleCode: "pa", name: "Punjabi", nativeName: "ਪੰਜਾਬੀ", flag: "🇮🇳", region: "Asia" },
  { code: "or", googleCode: "or", name: "Odia", nativeName: "ଓଡ଼ିଆ", flag: "🇮🇳", region: "Asia" },
  { code: "ne", googleCode: "ne", name: "Nepali", nativeName: "नेपाली", flag: "🇳🇵", region: "Asia" },
  { code: "si", googleCode: "si", name: "Sinhala", nativeName: "සිංහල", flag: "🇱🇰", region: "Asia" },
  { code: "as", googleCode: "as", name: "Assamese", nativeName: "অসমীয়া", flag: "🇮🇳", region: "Asia" },
  { code: "sd", googleCode: "sd", name: "Sindhi", nativeName: "سنڌي", flag: "🇵🇰", region: "Asia" },
  { code: "doi", googleCode: "doi", name: "Dogri", nativeName: "डोगरी", flag: "🇮🇳", region: "Asia" },
  { code: "bho", googleCode: "bho", name: "Bhojpuri", nativeName: "भोजपुरी", flag: "🇮🇳", region: "Asia" },
  { code: "mai", googleCode: "mai", name: "Maithili", nativeName: "मैथिली", flag: "🇮🇳", region: "Asia" },
  { code: "gom", googleCode: "gom", name: "Konkani", nativeName: "कोंकणी", flag: "🇮🇳", region: "Asia" },
  { code: "sa", googleCode: "sa", name: "Sanskrit", nativeName: "संस्कृतम्", flag: "🇮🇳", region: "Asia" },
  { code: "dv", googleCode: "dv", name: "Divehi", nativeName: "ދިވެހި", flag: "🇲🇻", region: "Asia" },

  // --- Southeast Asia ---
  { code: "th", googleCode: "th", name: "Thai", nativeName: "ไทย", flag: "🇹🇭", region: "Asia" },
  { code: "vi", googleCode: "vi", name: "Vietnamese", nativeName: "Tiếng Việt", flag: "🇻🇳", region: "Asia" },
  { code: "id", googleCode: "id", name: "Indonesian", nativeName: "Bahasa Indonesia", flag: "🇮🇩", region: "Asia" },
  { code: "ms", googleCode: "ms", name: "Malay", nativeName: "Bahasa Melayu", flag: "🇲🇾", region: "Asia" },
  { code: "tl", googleCode: "tl", name: "Filipino", nativeName: "Filipino", flag: "🇵🇭", region: "Asia" },
  { code: "km", googleCode: "km", name: "Khmer", nativeName: "ខ្មែរ", flag: "🇰🇭", region: "Asia" },
  { code: "lo", googleCode: "lo", name: "Lao", nativeName: "ລາວ", flag: "🇱🇦", region: "Asia" },
  { code: "my", googleCode: "my", name: "Myanmar (Burmese)", nativeName: "ဗမာ", flag: "🇲🇲", region: "Asia" },
  { code: "jw", googleCode: "jw", name: "Javanese", nativeName: "Jawa", flag: "🇮🇩", region: "Asia" },
  { code: "su", googleCode: "su", name: "Sundanese", nativeName: "Basa Sunda", flag: "🇮🇩", region: "Asia" },
  { code: "ceb", googleCode: "ceb", name: "Cebuano", nativeName: "Cebuano", flag: "🇵🇭", region: "Asia" },
  { code: "ilo", googleCode: "ilo", name: "Iloko", nativeName: "Iloko", flag: "🇵🇭", region: "Asia" },
  { code: "hil", googleCode: "hil", name: "Hiligaynon", nativeName: "Hiligaynon", flag: "🇵🇭", region: "Asia" },
  { code: "hmn", googleCode: "hmn", name: "Hmong", nativeName: "Hmoob", flag: "🇱🇦", region: "Asia" },

  // --- East Asia ---
  { code: "yue", googleCode: "yue", name: "Cantonese", nativeName: "粵語", flag: "🇭🇰", region: "Asia" },

  // --- Africa ---
  { code: "sw", googleCode: "sw", name: "Swahili", nativeName: "Kiswahili", flag: "🇹🇿", region: "Africa" },
  { code: "am", googleCode: "am", name: "Amharic", nativeName: "አማርኛ", flag: "🇪🇹", region: "Africa" },
  { code: "ha", googleCode: "ha", name: "Hausa", nativeName: "Hausa", flag: "🇳🇬", region: "Africa" },
  { code: "ig", googleCode: "ig", name: "Igbo", nativeName: "Igbo", flag: "🇳🇬", region: "Africa" },
  { code: "yo", googleCode: "yo", name: "Yoruba", nativeName: "Yorùbá", flag: "🇳🇬", region: "Africa" },
  { code: "zu", googleCode: "zu", name: "Zulu", nativeName: "isiZulu", flag: "🇿🇦", region: "Africa" },
  { code: "xh", googleCode: "xh", name: "Xhosa", nativeName: "isiXhosa", flag: "🇿🇦", region: "Africa" },
  { code: "af", googleCode: "af", name: "Afrikaans", nativeName: "Afrikaans", flag: "🇿🇦", region: "Africa" },
  { code: "sn", googleCode: "sn", name: "Shona", nativeName: "chiShona", flag: "🇿🇼", region: "Africa" },
  { code: "ny", googleCode: "ny", name: "Chichewa", nativeName: "Chichewa", flag: "🇲🇼", region: "Africa" },
  { code: "so", googleCode: "so", name: "Somali", nativeName: "Soomaaliga", flag: "🇸🇴", region: "Africa" },
  { code: "mg", googleCode: "mg", name: "Malagasy", nativeName: "Malagasy", flag: "🇲🇬", region: "Africa" },
  { code: "rw", googleCode: "rw", name: "Kinyarwanda", nativeName: "Kinyarwanda", flag: "🇷🇼", region: "Africa" },
  { code: "st", googleCode: "st", name: "Sesotho", nativeName: "Sesotho", flag: "🇱🇸", region: "Africa" },
  { code: "lg", googleCode: "lg", name: "Luganda", nativeName: "Oluganda", flag: "🇺🇬", region: "Africa" },
  { code: "om", googleCode: "om", name: "Oromo", nativeName: "Afaan Oromoo", flag: "🇪🇹", region: "Africa" },
  { code: "ti", googleCode: "ti", name: "Tigrinya", nativeName: "ትግርኛ", flag: "🇪🇷", region: "Africa" },
  { code: "ln", googleCode: "ln", name: "Lingala", nativeName: "Lingála", flag: "🇨🇩", region: "Africa" },
  { code: "bm", googleCode: "bm", name: "Bambara", nativeName: "Bamanankan", flag: "🇲🇱", region: "Africa" },
  { code: "ee", googleCode: "ee", name: "Ewe", nativeName: "Eʋegbe", flag: "🇬🇭", region: "Africa" },
  { code: "ak", googleCode: "ak", name: "Twi (Akan)", nativeName: "Twi", flag: "🇬🇭", region: "Africa" },
  { code: "nso", googleCode: "nso", name: "Northern Sotho", nativeName: "Sepedi", flag: "🇿🇦", region: "Africa" },
  { code: "ts", googleCode: "ts", name: "Tsonga", nativeName: "Xitsonga", flag: "🇿🇦", region: "Africa" },
  { code: "tn", googleCode: "tn", name: "Tswana", nativeName: "Setswana", flag: "🇧🇼", region: "Africa" },
  { code: "ss", googleCode: "ss", name: "Swati", nativeName: "siSwati", flag: "🇸🇿", region: "Africa" },
  { code: "nr", googleCode: "nr", name: "Ndebele (South)", nativeName: "isiNdebele", flag: "🇿🇦", region: "Africa" },

  // --- Americas ---
  { code: "ht", googleCode: "ht", name: "Haitian Creole", nativeName: "Kreyòl ayisyen", flag: "🇭🇹", region: "Americas" },
  { code: "gn", googleCode: "gn", name: "Guarani", nativeName: "Avañe'ẽ", flag: "🇵🇾", region: "Americas" },
  { code: "qu", googleCode: "qu", name: "Quechua", nativeName: "Runasimi", flag: "🇵🇪", region: "Americas" },
  { code: "ay", googleCode: "ay", name: "Aymara", nativeName: "Aymar aru", flag: "🇧🇴", region: "Americas" },
  { code: "haw", googleCode: "haw", name: "Hawaiian", nativeName: "ʻŌlelo Hawaiʻi", flag: "🇺🇸", region: "Americas" },

  // --- Pacific ---
  { code: "mi", googleCode: "mi", name: "Maori", nativeName: "Te Reo Māori", flag: "🇳🇿", region: "Pacific" },
  { code: "sm", googleCode: "sm", name: "Samoan", nativeName: "Gagana Samoa", flag: "🇼🇸", region: "Pacific" },
  { code: "fj", googleCode: "fj", name: "Fijian", nativeName: "Na Vosa Vakaviti", flag: "🇫🇯", region: "Pacific" },

  // --- Other / Constructed ---
  { code: "yi", googleCode: "yi", name: "Yiddish", nativeName: "ייִדיש", flag: "🇮🇱", region: "Europe" },
];

export const regions = [
  "Asia",
  "Europe",
  "Americas",
  "Middle East",
  "Africa",
  "Pacific",
] as const;

export type Region = (typeof regions)[number];
