# ፍኖተ ጽድቅ — የአባላት ምዝገባ ፕሮጀክት

## የሚያካትተው
- የአባላት ምዝገባ ገጽ፣ የአባላት ብዛት ማሳያ
- MySQL ዳታቤዝ (`gubre_db`) እና `members` ሰንጠረዥ
- በይለፍ ቃል የተጠበቀ የአስተዳዳሪ መግቢያ
- አባላትን መፈለግ፣ ማስተካከል፣ መሰረዝ እና CSV ማውረድ
- Prepared statements፣ CSRF token፣ የውጤት escaping እና የፎርም ማረጋገጫ

## 1. የሚያስፈልጉ
- Windows ላይ XAMPP (Apache, PHP 8+, MySQL/MariaDB)
- የድር አሳሽ

## 2. መጫኛ
1. ZIP ፋይሉን ያውርዱና extract ያድርጉ።
2. `finote_tsid_project` ፎልደሩን ወደ `C:\xampp\htdocs\finote_tsiddik` ይቅዱ።
3. XAMPP Control Panel ይክፈቱ፤ **Apache** እና **MySQL** ያስነሱ።
4. `http://localhost/phpmyadmin` ይክፈቱ።
5. **Import** ይጫኑ፣ `install.sql` ይምረጡና **Go** ይጫኑ።
6. በነባሪ XAMPP የMySQL መቼት ላይ `config.php` ውስጥ `root` እና ባዶ የይለፍ ቃል አለ። የእርስዎ MySQL የተለየ ይለፍ ቃል ካለው ያስተካክሉ።
7. በአሳሹ `http://localhost/finote_tsiddik/create_admin.php` ይክፈቱ። የአስተዳዳሪ ስምና ቢያንስ 12 ቁምፊ ያለው የይለፍ ቃል ይፍጠሩ።
8. **ከዚያ በኋላ `create_admin.php` ፋይሉን ወዲያውኑ ይሰርዙ**።
9. ዋናውን ገጽ በ `http://localhost/finote_tsiddik/` ይክፈቱ። አስተዳዳሪ መግቢያ፦ `http://localhost/finote_tsiddik/admin_login.php`

## 3. አስፈላጊ ማስታወሻዎች
- ይህ ፕሮጀክት ከእርስዎ ኮድ ጋር የሚስማማ አዲስ ዳታቤዝ ይፈጥራል። ነባር `gubre_db` ካለዎት ከማስመጣት በፊት የዳታቤዙን backup ያድርጉ። `CREATE TABLE IF NOT EXISTS` ያሉት ነባር ሰንጠረዦችን አያጠፉም።
- በቀጥታ ኢንተርኔት ላይ ለማውጣት HTTPS፣ ጠንካራ የዳታቤዝ ይለፍ ቃል፣ የፋይል ፈቃዶች እና የbackup ሂደት ያዘጋጁ። `config.php` ከድር ማውጫ ውጭ ማድረግ ይመከራል።
- የአባላት መረጃ ግላዊ ነው፤ የአስተዳዳሪ መለያዎችን ለታመኑ ሰዎች ብቻ ይስጡ።
- የGoogle Fonts ማስመጫው ኢንተርኔት ካለ ይሰራል፤ ከሌለ የአካባቢው ፎንቶች ይጠቀማሉ።
