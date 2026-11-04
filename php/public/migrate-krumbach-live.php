<?php
/**
 * Einmalige Migration für hanstudios.de nach dem Krumbach-Umschwenken.
 *
 * Aktualisiert in der Live-Datenbank: Kontakt, Hero, Statistiken, Rechtstexte
 * (Impressum + Datenschutz), Marketing-SEO-Vorlagen und Portfolio-/Stimmen-
 * Defaults. Lässt die bereits gepflegten Stadt- und Beitragsseiten in Ruhe –
 * die sind über bin/cities.php und bin/seed-*.php schon auf Krumbach gesetzt.
 *
 * Aufruf nach dem FTPS-Upload (All-Inkl KAS hat kein SSH):
 *
 *   https://hanstudios.de/migrate-krumbach-live.php?token=<GEHEIM>
 *
 * Das Skript liegt dafür vorübergehend unter public/. Nach Erfolg sofort
 * löschen. Der Token wird unten als Konstante gesetzt; einmal verwenden und
 * die Datei vom Server entfernen.
 */
declare(strict_types=1);

// Nur über HTTPS und nur mit Token erlauben – minimale Absicherung ist genug,
// weil die Datei nach dem Lauf sowieso wieder weg ist.
const MIGRATE_TOKEN = 'KRUMBACH-2026-11-04';

if (($_GET['token'] ?? '') !== MIGRATE_TOKEN) {
    http_response_code(403);
    echo "verboten\n";
    exit;
}

require __DIR__ . '/../src/bootstrap.php';

use Atelier\Content;

$impressumSections = [
    [
        'heading' => [
            'de' => 'Angaben gemäß § 5 DDG',
            'tr' => '§ 5 DDG uyarınca bilgiler',
            'en' => 'Information according to § 5 DDG',
        ],
        'body' => [
            'de' => "{legalName}\nInhaber: {owner}\n{street}\n{zip} {city}\nDeutschland",
            'tr' => "{legalName}\nSahibi: {owner}\n{street}\n{zip} {city}\nAlmanya",
            'en' => "{legalName}\nOwner: {owner}\n{street}\n{zip} {city}\nGermany",
        ],
    ],
    [
        'heading' => ['de' => 'Kontakt', 'tr' => 'İletişim', 'en' => 'Contact'],
        'body' => [
            'de' => "Telefon: {phone}\nE-Mail: {email}",
            'tr' => "Telefon: {phone}\nE-posta: {email}",
            'en' => "Phone: {phone}\nEmail: {email}",
        ],
    ],
    [
        'heading' => ['de' => 'Geschäftstätigkeit', 'tr' => 'Faaliyet alanı', 'en' => 'Business activity'],
        'body' => [
            'de' => 'Hochzeitsfotografie und Hochzeitsvideografie.',
            'tr' => 'Düğün fotoğrafçılığı ve düğün videografisi.',
            'en' => 'Wedding photography and wedding videography.',
        ],
    ],
    [
        'heading' => ['de' => 'Umsatzsteuer', 'tr' => 'KDV', 'en' => 'VAT'],
        'body' => [
            'de' => 'Kein Umsatzsteuerausweis aufgrund der Anwendung der Kleinunternehmerregelung gemäß § 19 UStG.',
            'tr' => '§ 19 UStG kapsamındaki küçük işletme düzenlemesi nedeniyle KDV ayrıca gösterilmez.',
            'en' => 'No separate VAT indication due to application of the small business regulation under § 19 UStG.',
        ],
    ],
    [
        'heading' => ['de' => 'Haftung für Inhalte', 'tr' => 'İçerik sorumluluğu', 'en' => 'Liability for content'],
        'body' => [
            'de' => "Die Inhalte unserer Website wurden mit größtmöglicher Sorgfalt erstellt. Wir bemühen uns, die Informationen aktuell, vollständig und richtig zu halten. Dennoch übernehmen wir keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität sämtlicher Inhalte, soweit gesetzlich zulässig.\n\nAls Diensteanbieter sind wir für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Wir sind jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen ohne konkrete Anhaltspunkte auf rechtswidrige Tätigkeiten zu überwachen.\n\nGesetzliche Verpflichtungen zur Entfernung oder Sperrung rechtswidriger Inhalte bleiben hiervon unberührt. Sobald uns konkrete Rechtsverletzungen bekannt werden, werden wir die betreffenden Inhalte im Rahmen unserer gesetzlichen Verpflichtungen prüfen und gegebenenfalls entfernen.",
            'tr' => '',
            'en' => '',
        ],
    ],
    [
        'heading' => ['de' => 'Haftung für externe Links', 'tr' => 'Dış bağlantılar için sorumluluk', 'en' => 'Liability for external links'],
        'body' => [
            'de' => "Unsere Website enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen unmittelbaren Einfluss haben. Deshalb können wir für diese fremden Inhalte keine Gewähr übernehmen.\n\nFür die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber verantwortlich.\n\nZum Zeitpunkt der Verlinkung waren keine konkreten Rechtsverstöße erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar.\n\nBei Bekanntwerden von Rechtsverletzungen werden wir entsprechende Links prüfen und gegebenenfalls entfernen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => 'Haftung für technische Verfügbarkeit', 'tr' => 'Teknik erişilebilirlik sorumluluğu', 'en' => 'Liability for technical availability'],
        'body' => [
            'de' => "Wir bemühen uns, unsere Website möglichst störungsfrei und dauerhaft verfügbar zu halten. Eine ununterbrochene Verfügbarkeit kann jedoch aufgrund technischer Störungen, Wartungsarbeiten oder Einflüssen außerhalb unseres Verantwortungsbereichs nicht garantiert werden.\n\nGesetzliche Ansprüche bleiben hiervon unberührt.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => 'Urheberrecht und Bildrechte', 'tr' => 'Telif ve görsel hakları', 'en' => 'Copyright and image rights'],
        'body' => [
            'de' => "Die auf dieser Website veröffentlichten Inhalte, insbesondere Fotografien, Videos, Grafiken, Texte, Logos und sonstige kreative Werke, unterliegen dem deutschen Urheberrecht und weiteren gesetzlichen Schutzrechten.\n\nSoweit die Inhalte von {legalName} erstellt wurden, liegen die entsprechenden Urheberrechte bei {owner} beziehungsweise bei den jeweiligen Rechteinhabern, sofern keine abweichenden Vereinbarungen bestehen.\n\nJede Vervielfältigung, Bearbeitung, Verbreitung, Veröffentlichung oder sonstige Verwertung außerhalb der gesetzlichen Grenzen bedarf der vorherigen Zustimmung des jeweiligen Rechteinhabers.\n\nDas Herunterladen, Kopieren oder die kommerzielle Nutzung von Fotografien und Videos ohne entsprechende Berechtigung ist nicht gestattet.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => 'Verwendung von Hochzeitsfotografien und Videos', 'tr' => 'Düğün fotoğrafı ve videolarının kullanımı', 'en' => 'Use of wedding photos and videos'],
        'body' => [
            'de' => "Die auf unserer Website gezeigten Hochzeitsfotografien und Videos dienen der Präsentation unserer fotografischen und filmischen Arbeit.\n\nDie Veröffentlichung von Aufnahmen, auf denen Personen erkennbar sind, erfolgt unter Beachtung der jeweils geltenden datenschutz- und persönlichkeitsrechtlichen Bestimmungen sowie der erforderlichen Einwilligungen beziehungsweise sonstigen Rechtsgrundlagen.\n\nDie Rechte an Kundenbildern und Kundenfilmen richten sich nach den jeweils getroffenen Vereinbarungen und den geltenden gesetzlichen Bestimmungen.\n\nBei Fragen zu veröffentlichten Inhalten oder Hinweisen auf mögliche Rechtsverletzungen kontaktieren Sie uns bitte unter:\n\n{email}",
            'tr' => '', 'en' => '',
        ],
    ],
];

$datenschutzSections = [
    [
        'heading' => ['de' => '1. Verantwortlicher', 'tr' => '1. Sorumlu', 'en' => '1. Controller'],
        'body' => [
            'de' => "Verantwortlich für die Verarbeitung personenbezogener Daten auf dieser Website ist:\n\n**{owner} – {legalName}**\n\n{street}\n{zip} {city}\nDeutschland\n\nTelefon: {phone}\nE-Mail: {email}",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '2. Allgemeine Hinweise zur Datenverarbeitung', 'tr' => '2. Veri işleme hakkında genel bilgi', 'en' => '2. General information on data processing'],
        'body' => [
            'de' => "Der Schutz Ihrer personenbezogenen Daten ist uns wichtig.\n\nWir verarbeiten personenbezogene Daten ausschließlich im Rahmen der geltenden gesetzlichen Datenschutzbestimmungen, insbesondere der Datenschutz-Grundverordnung (DSGVO) und des Telekommunikation-Digitale-Dienste-Datenschutz-Gesetzes (TDDDG).\n\nPersonenbezogene Daten sind alle Informationen, mit denen eine Person direkt oder indirekt identifiziert werden kann.\n\nDie Verarbeitung erfolgt insbesondere zur Bereitstellung unserer Website, zur Bearbeitung von Anfragen, zur Durchführung vorvertraglicher Maßnahmen sowie zur Erbringung unserer Foto- und Videodienstleistungen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '3. Hosting durch ALL-INKL', 'tr' => '3. ALL-INKL üzerinden barındırma', 'en' => '3. Hosting with ALL-INKL'],
        'body' => [
            'de' => "Unsere Website wird bei folgendem Anbieter gehostet:\n\nALL-INKL.COM – Neue Medien Münnich\nInhaber: René Münnich\nHauptstraße 68\n02742 Friedersdorf\nDeutschland\n\nWebsite: [all-inkl.com](https://all-inkl.com/)\n\nBeim Besuch unserer Website werden durch den Hosting-Anbieter technisch erforderliche Informationen verarbeitet. Dazu können insbesondere folgende Daten gehören:\n\n- IP-Adresse des anfragenden Geräts\n- Datum und Uhrzeit des Zugriffs\n- aufgerufene Seiten und Dateien\n- übertragene Datenmenge\n- Browsertyp und Browserversion\n- verwendetes Betriebssystem\n- Referrer-URL\n- technische Statusmeldungen\n\nDie Verarbeitung erfolgt zur technischen Bereitstellung, Stabilität und Sicherheit unserer Website.\n\nRechtsgrundlage ist Artikel 6 Absatz 1 Buchstabe f DSGVO, soweit die Verarbeitung zur Wahrung unserer berechtigten Interessen erforderlich ist.\n\nSoweit ALL-INKL personenbezogene Daten in unserem Auftrag verarbeitet, erfolgt dies auf Grundlage einer Vereinbarung zur Auftragsverarbeitung gemäß Artikel 28 DSGVO.\n\nWeitere Informationen zum Datenschutz bei ALL-INKL: [all-inkl.com/datenschutzinformationen](https://all-inkl.com/datenschutzinformationen/)",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '4. Kontaktaufnahme und Kontaktformular', 'tr' => '4. İletişim ve iletişim formu', 'en' => '4. Contact and contact form'],
        'body' => [
            'de' => "Auf unserer Website befindet sich ein selbst programmiertes Kontaktformular.\n\nWenn Sie uns über dieses Formular kontaktieren, verarbeiten wir die von Ihnen eingegebenen personenbezogenen Daten zur Bearbeitung Ihrer Anfrage.\n\nDabei können insbesondere folgende Angaben verarbeitet werden:\n\n- Vor- und Nachname\n- E-Mail-Adresse\n- Telefonnummer\n- Hochzeitsdatum\n- Veranstaltungsort\n- gewünschte Dienstleistungen\n- individuelle Nachricht\n\nDie Verarbeitung erfolgt zur Beantwortung Ihrer Anfrage sowie gegebenenfalls zur Vorbereitung oder Durchführung eines Vertrags.\n\nRechtsgrundlagen:\n\n- Artikel 6 Absatz 1 Buchstabe b DSGVO bei vorvertraglichen Maßnahmen und Vertragsdurchführung.\n- Artikel 6 Absatz 1 Buchstabe f DSGVO bei sonstigen Anfragen, sofern die gesetzlichen Voraussetzungen erfüllt sind.\n\nDie übermittelten Daten werden gelöscht, sobald sie für die Bearbeitung der Anfrage nicht mehr erforderlich sind und keine gesetzlichen Aufbewahrungspflichten entgegenstehen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '5. Google Fonts', 'tr' => '5. Google Fonts', 'en' => '5. Google Fonts'],
        'body' => [
            'de' => "Unsere Website verwendet Schriftarten von Google Fonts.\n\nDie Schriftarten werden derzeit von Google-Servern geladen. Beim Aufruf einer Seite, auf der diese Schriftarten eingebunden sind, kann eine Verbindung zu Google hergestellt werden.\n\nDabei kann insbesondere die IP-Adresse des Besuchers an Google übermittelt werden. Weitere technische Informationen können abhängig von der Einbindung verarbeitet werden.\n\nDie datenschutzrechtliche Zulässigkeit dieser Einbindung wird unter Berücksichtigung der geltenden Datenschutzbestimmungen geprüft.\n\nEine lokale Einbindung der Schriftarten auf unserem eigenen Server kann eine Übermittlung der IP-Adresse an Google beim Abruf der Schriftdateien vermeiden.\n\nWeitere Informationen zum Datenschutz bei Google: [policies.google.com/privacy](https://policies.google.com/privacy)",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '6. Google Maps', 'tr' => '6. Google Maps', 'en' => '6. Google Maps'],
        'body' => [
            'de' => "Auf unserer Website verwenden wir Google Maps zur Darstellung von Karten und zur geografischen Orientierung.\n\nAnbieter ist Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland.\n\nBei einer direkten Einbindung von Google Maps kann bereits beim Laden der entsprechenden Website eine Verbindung zu Google hergestellt werden.\n\nDabei können insbesondere folgende Informationen verarbeitet werden:\n\n- IP-Adresse\n- technische Geräteinformationen\n- Browserinformationen\n- Nutzungsdaten\n- gegebenenfalls Standortinformationen\n\nSoweit eine Einwilligung erforderlich ist, wird die Karte erst nach entsprechender Zustimmung über unser Einwilligungsbanner geladen.\n\nRechtsgrundlage ist Artikel 6 Absatz 1 Buchstabe a DSGVO in Verbindung mit § 25 Absatz 1 TDDDG, soweit die jeweiligen Voraussetzungen vorliegen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '7. YouTube', 'tr' => '7. YouTube', 'en' => '7. YouTube'],
        'body' => [
            'de' => "Unsere Website enthält Verlinkungen zu Videos und Inhalten auf YouTube.\n\nAnbieter ist Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland.\n\nWenn Sie einen YouTube-Link anklicken, werden Sie auf die externe Plattform weitergeleitet. Erst durch den Aufruf der externen Website erfolgt die dortige Datenverarbeitung durch den jeweiligen Anbieter.\n\nBei einer bloßen Verlinkung wird das YouTube-Video nicht automatisch auf unserer Website geladen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '8. Selbst entwickelte Onlinegalerie', 'tr' => '8. Kendi geliştirdiğimiz online galeri', 'en' => '8. Self-developed online gallery'],
        'body' => [
            'de' => "{legalName} stellt seinen Kunden eine selbst entwickelte Onlinegalerie zur Verfügung, über die Hochzeitsfotografien und gegebenenfalls Videos angesehen und bereitgestellt werden können.\n\nDie Onlinegalerie wurde von {legalName} selbst programmiert und wird von uns verwaltet.\n\nIm Rahmen der Nutzung können folgende personenbezogene Daten verarbeitet werden:\n\n- Hochzeitsfotografien und Videodateien\n- Namen, sofern diese in der Galerie verwendet werden\n- individuelle Galerie-Links und Zugangsdaten\n- technische Zugriffsdaten\n- IP-Adressen und Zeitpunkte der Zugriffe, soweit diese technisch protokolliert werden\n\nDie Verarbeitung erfolgt zur Bereitstellung der vereinbarten Foto- und Videodienstleistungen.\n\nRechtsgrundlage ist Artikel 6 Absatz 1 Buchstabe b DSGVO, soweit die Verarbeitung zur Vertragserfüllung erforderlich ist. Für darüber hinausgehende Verarbeitungen ist gegebenenfalls eine gesonderte Rechtsgrundlage erforderlich.\n\nDer Zugang zu den Galerien kann durch individuelle Links oder weitere Zugangsbeschränkungen geregelt werden.\n\nDie gespeicherten Fotografien und Videos werden entsprechend den vertraglichen Vereinbarungen und den jeweiligen Speicherfristen aufbewahrt und anschließend gelöscht, sofern keine gesetzlichen Aufbewahrungspflichten oder sonstigen rechtmäßigen Gründe für eine weitere Speicherung bestehen.\n\nDie technische Bereitstellung und Speicherung erfolgt über die eingesetzte Hosting-Infrastruktur. Soweit hierfür ALL-INKL genutzt wird, gelten ergänzend die Angaben zum Hosting in Abschnitt 3.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '9. Cookies und Einwilligungsverwaltung', 'tr' => '9. Çerezler ve onay yönetimi', 'en' => '9. Cookies and consent management'],
        'body' => [
            'de' => "Unsere Website verfügt über ein selbst programmiertes Cookie- und Einwilligungsbanner.\n\nÜber die bereitgestellten Datenschutzeinstellungen können Besucher ihre Einwilligungsentscheidungen verwalten und jederzeit ändern oder widerrufen, soweit die jeweilige Funktion dies ermöglicht.\n\nCookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden können. Sie können beispielsweise zur Bereitstellung technischer Funktionen, zur Speicherung von Einstellungen oder zur statistischen Auswertung verwendet werden.\n\nTechnisch nicht erforderliche Cookies und vergleichbare Technologien, für die eine Einwilligung erforderlich ist, werden erst nach einer entsprechenden Einwilligung aktiviert.\n\nDie Rechtsgrundlage richtet sich nach dem jeweiligen Verarbeitungszweck. Insbesondere ist § 25 TDDDG für das Speichern von Informationen auf Endgeräten und den Zugriff auf bereits gespeicherte Informationen zu beachten.\n\n{{consent}}",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '10. Google Analytics', 'tr' => '10. Google Analytics', 'en' => '10. Google Analytics'],
        'body' => [
            'de' => "Wir beabsichtigen, auf unserer Website Google Analytics einzusetzen.\n\nGoogle Analytics ist ein Webanalysedienst von Google, mit dem die Nutzung unserer Website statistisch ausgewertet werden kann.\n\nAnbieter ist Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland. Je nach Konfiguration können weitere Google-Unternehmen an der Verarbeitung beteiligt sein.\n\n**Zweck der Verarbeitung:** Statistische Auswertung der Website-Nutzung sowie Verbesserung der Inhalte, Funktionen und Benutzerfreundlichkeit unserer Website.\n\n**Rechtsgrundlage:** Artikel 6 Absatz 1 Buchstabe a DSGVO in Verbindung mit § 25 Absatz 1 TDDDG, soweit eine Einwilligung für das Speichern von Informationen auf dem Endgerät oder den Zugriff darauf erforderlich ist.\n\nGoogle Analytics wird erst nach Erteilung der erforderlichen Einwilligung über unser Cookie- und Einwilligungsbanner aktiviert.\n\n**Hinweis zum aktuellen Stand:** Google Analytics ist zum Zeitpunkt der Erstellung dieser Datenschutzerklärung noch nicht aktiviert. Die technische Einrichtung und die Einbindung in unser Einwilligungsbanner erfolgen zu einem späteren Zeitpunkt.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '11. Weitergabe personenbezogener Daten', 'tr' => '11. Verilerin aktarılması', 'en' => '11. Sharing of personal data'],
        'body' => [
            'de' => "Eine Weitergabe personenbezogener Daten an Dritte erfolgt nur, wenn dies:\n\n- zur Erfüllung eines Vertrags erforderlich ist,\n- zur Bearbeitung einer Anfrage notwendig ist,\n- aufgrund einer gesetzlichen Verpflichtung erforderlich ist,\n- auf einer wirksamen Einwilligung beruht oder\n- auf einer sonstigen gesetzlichen Rechtsgrundlage zulässig ist.\n\nTechnische Dienstleister können personenbezogene Daten im Rahmen ihrer jeweiligen Aufgaben verarbeiten. Soweit erforderlich, werden entsprechende Vereinbarungen zur Auftragsverarbeitung abgeschlossen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '12. Speicherdauer', 'tr' => '12. Saklama süresi', 'en' => '12. Storage period'],
        'body' => [
            'de' => "Personenbezogene Daten werden nur so lange gespeichert, wie dies für den jeweiligen Verarbeitungszweck erforderlich ist oder gesetzliche Aufbewahrungspflichten bestehen.\n\nNach Wegfall des jeweiligen Zwecks werden die Daten gelöscht, sofern keine gesetzlichen oder sonstigen rechtmäßigen Gründe für eine weitere Speicherung bestehen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '13. Rechte betroffener Personen', 'tr' => '13. İlgili kişilerin hakları', 'en' => '13. Rights of data subjects'],
        'body' => [
            'de' => "Sie haben im Rahmen der gesetzlichen Voraussetzungen insbesondere folgende Rechte:\n\n- **Auskunft:** Recht auf Auskunft über Ihre gespeicherten personenbezogenen Daten gemäß Artikel 15 DSGVO.\n- **Berichtigung:** Recht auf Berichtigung unrichtiger Daten gemäß Artikel 16 DSGVO.\n- **Löschung:** Recht auf Löschung Ihrer personenbezogenen Daten gemäß Artikel 17 DSGVO.\n- **Einschränkung:** Recht auf Einschränkung der Verarbeitung gemäß Artikel 18 DSGVO.\n- **Datenübertragbarkeit:** Recht auf Datenübertragbarkeit gemäß Artikel 20 DSGVO.\n- **Widerspruch:** Recht auf Widerspruch gegen bestimmte Verarbeitungen gemäß Artikel 21 DSGVO.\n- **Widerruf:** Recht auf Widerruf einer erteilten Einwilligung mit Wirkung für die Zukunft gemäß Artikel 7 Absatz 3 DSGVO.\n\nZur Ausübung Ihrer Rechte können Sie uns jederzeit unter den oben genannten Kontaktdaten erreichen.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '14. Beschwerderecht', 'tr' => '14. Şikayet hakkı', 'en' => '14. Right to complain'],
        'body' => [
            'de' => "Sie haben das Recht, sich bei einer Datenschutzaufsichtsbehörde zu beschweren, wenn Sie der Ansicht sind, dass die Verarbeitung Ihrer personenbezogenen Daten gegen geltendes Datenschutzrecht verstößt.\n\nFür Bayern ist insbesondere das Bayerische Landesamt für Datenschutzaufsicht zuständig, soweit es sich um eine Angelegenheit innerhalb seines Zuständigkeitsbereichs handelt.\n\nWebsite: [lda.bayern.de](https://www.lda.bayern.de/)",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '15. Datensicherheit', 'tr' => '15. Veri güvenliği', 'en' => '15. Data security'],
        'body' => [
            'de' => "Wir setzen angemessene technische und organisatorische Maßnahmen ein, um personenbezogene Daten vor Verlust, Manipulation und unberechtigtem Zugriff zu schützen.\n\nDie Kommunikation mit unserer Website erfolgt über eine verschlüsselte Verbindung, sofern die jeweilige Verbindung über HTTPS hergestellt wird.\n\nWir weisen darauf hin, dass eine vollständige Sicherheit bei der Datenübertragung im Internet nicht garantiert werden kann.",
            'tr' => '', 'en' => '',
        ],
    ],
    [
        'heading' => ['de' => '16. Aktualität und Änderungen', 'tr' => '16. Güncellik ve değişiklikler', 'en' => '16. Updates and changes'],
        'body' => [
            'de' => "Wir behalten uns vor, diese Datenschutzerklärung anzupassen, wenn sich die technischen Funktionen unserer Website, die eingesetzten Dienste oder die gesetzlichen Datenschutzanforderungen ändern.\n\nDie jeweils aktuelle Fassung wird auf unserer Website veröffentlicht.",
            'tr' => '', 'en' => '',
        ],
    ],
];

Content::mutate(function (array $c) use ($impressumSections, $datenschutzSections): array {
    // 1. Kontakt
    $c['contact'] = array_merge($c['contact'] ?? [], [
        'phone'      => '+491743283263',
        'phoneHuman' => '0174 3283263',
        'email'      => 'info@hanstudio.de',
        'street'     => 'Dr.-Sprandelstraße 1',
        'zip'        => '86381',
        'city'       => 'Krumbach',
    ]);

    // 2. Hero (yeni H1 + slogan altbaşlık)
    $c['hero'] = [
        'eyebrow'  => [
            'de' => 'Hochzeitsfotografie & Film · Krumbach · Ulm · Memmingen · Augsburg',
            'tr' => 'Düğün Fotoğrafçılığı & Film · Krumbach · Ulm · Memmingen · Augsburg',
            'en' => 'Wedding Photography & Film · Krumbach · Ulm · Memmingen · Augsburg',
        ],
        'title'    => [
            'de' => 'Hochzeitsfotograf & Hochzeitsfilm in Krumbach, Ulm, Memmingen und Augsburg',
            'tr' => "Krumbach, Ulm, Memmingen ve Augsburg'da düğün fotoğrafçısı ve düğün filmi",
            'en' => 'Wedding photographer & film in Krumbach, Ulm, Memmingen and Augsburg',
        ],
        'subtitle' => [
            'de' => 'Der Tag vergeht. Die Bilder bleiben.',
            'tr' => 'Gün geçer. Kareler kalır.',
            'en' => 'The day passes. The pictures remain.',
        ],
        'text'     => [
            'de' => 'Dokumentarische Hochzeitsfotografie und Film in Schwaben und im Allgäu. Ruhig gearbeitet, ehrlich erzählt – und pünktlich geliefert.',
            'tr' => "Schwaben ve Allgäu'da belgesel tarzda düğün fotoğrafçılığı ve film. Sakin çalışılmış, dürüst anlatılmış – ve zamanında teslim edilmiş.",
            'en' => 'Documentary wedding photography and film in Swabia and the Allgäu. Worked quietly, told honestly – and delivered on time.',
        ],
    ];

    // 3. Uydurma sayı yok
    $c['stats'] = ['weddings' => '', 'years' => '', 'delivery' => '3', 'rating' => ''];

    // 4. Uydurma yorum ve çekim yok
    $c['testimonials'] = [];
    $c['stories'] = [];

    // 5. Rechtstexte: Impressum + Datenschutz full text
    $c['legal'] = $c['legal'] ?? [];
    $c['legal']['impressum'] = [
        'title' => ['de' => 'Impressum', 'tr' => 'Künye', 'en' => 'Legal notice'],
        'sections' => $impressumSections,
        'note' => ['de' => 'Stand: November 2026', 'tr' => '', 'en' => ''],
    ];
    $c['legal']['datenschutz'] = [
        'title' => ['de' => 'Datenschutzerklärung', 'tr' => 'Gizlilik politikası', 'en' => 'Privacy policy'],
        'sections' => $datenschutzSections,
        'note' => ['de' => 'Stand: November 2026', 'tr' => '', 'en' => ''],
    ];

    // 6. Marketing SEO: "ab 690 €" kaldır, Krumbach başlıkları
    $c['marketing'] = $c['marketing'] ?? [];
    $c['marketing']['templates'] = $c['marketing']['templates'] ?? [];
    $c['marketing']['templates']['city'] = [
        'de' => 'Hochzeitsfotograf {name} – Foto & Video | Han Studios',
        'tr' => '{name} Düğün Fotoğrafçısı – Foto & Video | Han Studios',
        'en' => 'Wedding photographer {name} – Photo & Film | Han Studios',
    ];

    $c['marketing']['pages'] = $c['marketing']['pages'] ?? [];
    $c['marketing']['pages']['home'] = [
        'title' => [
            'de' => 'Hochzeitsfotograf Krumbach · Ulm · Memmingen · Augsburg | Han Studios',
            'tr' => 'Krumbach · Ulm · Memmingen · Augsburg Düğün Fotoğrafçısı | Han Studios',
            'en' => 'Wedding photographer Krumbach · Ulm · Memmingen · Augsburg | Han Studios',
        ],
        'description' => [
            'de' => 'Dokumentarische Hochzeitsfotografie und Hochzeitsfilm in Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg und Günzburg. Private Kundengalerie und digitale Hochzeitseinladung inklusive.',
            'tr' => "Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg ve Günzburg'da belgesel tarzda düğün fotoğrafçılığı ve düğün filmi. Özel müşteri galerisi ve dijital düğün davetiyesi dahil.",
            'en' => 'Documentary wedding photography and film in Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg and Günzburg. Private client gallery and digital invitation included.',
        ],
        'noindex' => false,
        'image' => '',
    ];
    $c['marketing']['pages']['preise'] = [
        'title' => [
            'de' => 'Preise Hochzeitsfotograf – Pakete für Krumbach, Ulm & Augsburg',
            'tr' => 'Düğün fotoğrafçısı fiyatları – Krumbach, Ulm ve Augsburg paketleri',
            'en' => 'Wedding photographer prices – Packages for Krumbach, Ulm & Augsburg',
        ],
        'description' => [
            'de' => 'Transparente Pakete für Standesamt, Ganztagesreportage und kombinierte Foto- und Filmbegleitung. Alles inklusive, keine versteckten Kosten.',
            'tr' => 'Nikah, tam gün çekim ve foto+film birlikte paketleri; şeffaf fiyatlar, gizli maliyet yok.',
            'en' => 'Transparent packages for registry office, full-day reportage and combined photo & film. Everything included, no hidden costs.',
        ],
        'noindex' => false, 'image' => '',
    ];
    $c['marketing']['pages']['regionen'] = [
        'title' => [
            'de' => 'Hochzeitsfotograf zwischen Ulm, Memmingen und Augsburg – alle Regionen',
            'tr' => 'Ulm, Memmingen ve Augsburg arası düğün fotoğrafçısı – tüm bölgeler',
            'en' => 'Wedding photographer between Ulm, Memmingen and Augsburg – all regions',
        ],
        'description' => [
            'de' => 'Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg und Günzburg: Hochzeitsfotograf und Videograf mit Ortskenntnis in Schwaben und im Allgäu.',
            'tr' => "Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg ve Günzburg: Schwaben ve Allgäu'da bölgeyi tanıyan düğün fotoğrafçısı ve videografı.",
            'en' => 'Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg and Günzburg: wedding photographer and videographer with local knowledge in Swabia and the Allgäu.',
        ],
        'noindex' => false, 'image' => '',
    ];
    $c['marketing']['pages']['kontakt'] = [
        'title' => [
            'de' => 'Kontakt – Hochzeitsfotograf in Krumbach anfragen',
            'tr' => "İletişim – Krumbach'ta düğün fotoğrafçısı",
            'en' => 'Contact – wedding photographer in Krumbach',
        ],
        'description' => [
            'de' => 'Fragt euer Hochzeitsdatum an. Antwort in der Regel innerhalb von 24 Stunden. Atelier in Krumbach, Einzugsgebiet Ulm, Memmingen und Augsburg.',
            'tr' => "Düğün tarihinizi sorun. Genelde 24 saat içinde yanıt. Atölye Krumbach'ta; Ulm, Memmingen ve Augsburg hizmet alanı.",
            'en' => 'Request your wedding date. Response usually within 24 hours. Studio in Krumbach, serving Ulm, Memmingen and Augsburg.',
        ],
        'noindex' => false, 'image' => '',
    ];

    return $c;
});

echo "OK — Krumbach migration angewandt.\n";
echo "Bitte diese Datei JETZT vom Server löschen (public/migrate-krumbach-live.php).\n";
