/* ------------------------------------------------------------------ */
/*  Rechtstexte – Standardinhalte                                      */
/*                                                                     */
/*  Bewusst nur auf Deutsch: Impressum, Datenschutzerklärung und AGB   */
/*  richten sich nach deutschem Recht und gelten auch unter /tr.       */
/*                                                                     */
/*  Mini-Auszeichnung im Fließtext (siehe components/LegalBody.tsx):   */
/*    Leerzeile        -> neuer Absatz                                 */
/*    "- " am Zeilen-  -> Aufzählungspunkt                             */
/*      anfang                                                         */
/*    **fett**         -> Hervorhebung                                 */
/*    `code`           -> Monospace                                    */
/*    [Text](URL)      -> Link                                         */
/*    {{consent}}      -> Button "Cookie-Einstellungen ändern"         */
/*                                                                     */
/*  Platzhalter, die aus den Kontaktdaten im Admin gefüllt werden:     */
/*    {legalName} {owner} {street} {zip} {city} {email} {phone}        */
/* ------------------------------------------------------------------ */

export type LegalSection = { heading: string; body: string };

export type LegalPage = {
  title: string;
  sections: LegalSection[];
  /** Kleingedruckter Hinweis am Seitenende (leer = ausgeblendet) */
  note: string;
};

export type LegalKey = "impressum" | "datenschutz" | "agb";
export type LegalContent = Record<LegalKey, LegalPage>;

export const legalPageOrder: LegalKey[] = ["impressum", "datenschutz", "agb"];

export const legal: LegalContent = {
  impressum: {
    title: "Impressum",
    sections: [
      {
        heading: "Angaben gemäß § 5 DDG",
        body: "{legalName}\nInhaber: {owner}\n{street}\n{zip} {city}\nDeutschland",
      },
      {
        heading: "Kontakt",
        body: "Telefon: {phone}\nE-Mail: {email}",
      },
      {
        heading: "Geschäftstätigkeit",
        body: "Hochzeitsfotografie und Hochzeitsvideografie.",
      },
      {
        heading: "Umsatzsteuer",
        body: "Kein Umsatzsteuerausweis aufgrund der Anwendung der Kleinunternehmerregelung gemäß § 19 UStG.",
      },
      {
        heading: "Haftung für Inhalte",
        body: "Die Inhalte unserer Website wurden mit größtmöglicher Sorgfalt erstellt. Wir bemühen uns, die Informationen aktuell, vollständig und richtig zu halten. Dennoch übernehmen wir keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität sämtlicher Inhalte, soweit gesetzlich zulässig.\n\nAls Diensteanbieter sind wir für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Wir sind jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen ohne konkrete Anhaltspunkte auf rechtswidrige Tätigkeiten zu überwachen.\n\nGesetzliche Verpflichtungen zur Entfernung oder Sperrung rechtswidriger Inhalte bleiben hiervon unberührt. Sobald uns konkrete Rechtsverletzungen bekannt werden, werden wir die betreffenden Inhalte im Rahmen unserer gesetzlichen Verpflichtungen prüfen und gegebenenfalls entfernen.",
      },
      {
        heading: "Haftung für externe Links",
        body: "Unsere Website enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen unmittelbaren Einfluss haben. Deshalb können wir für diese fremden Inhalte keine Gewähr übernehmen.\n\nFür die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber verantwortlich.\n\nZum Zeitpunkt der Verlinkung waren keine konkreten Rechtsverstöße erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar.\n\nBei Bekanntwerden von Rechtsverletzungen werden wir entsprechende Links prüfen und gegebenenfalls entfernen.",
      },
      {
        heading: "Haftung für technische Verfügbarkeit",
        body: "Wir bemühen uns, unsere Website möglichst störungsfrei und dauerhaft verfügbar zu halten. Eine ununterbrochene Verfügbarkeit kann jedoch aufgrund technischer Störungen, Wartungsarbeiten oder Einflüssen außerhalb unseres Verantwortungsbereichs nicht garantiert werden.\n\nGesetzliche Ansprüche bleiben hiervon unberührt.",
      },
      {
        heading: "Urheberrecht und Bildrechte",
        body: "Die auf dieser Website veröffentlichten Inhalte, insbesondere Fotografien, Videos, Grafiken, Texte, Logos und sonstige kreative Werke, unterliegen dem deutschen Urheberrecht und weiteren gesetzlichen Schutzrechten.\n\nSoweit die Inhalte von {legalName} erstellt wurden, liegen die entsprechenden Urheberrechte bei {owner} beziehungsweise bei den jeweiligen Rechteinhabern, sofern keine abweichenden Vereinbarungen bestehen.\n\nJede Vervielfältigung, Bearbeitung, Verbreitung, Veröffentlichung oder sonstige Verwertung außerhalb der gesetzlichen Grenzen bedarf der vorherigen Zustimmung des jeweiligen Rechteinhabers.\n\nDas Herunterladen, Kopieren oder die kommerzielle Nutzung von Fotografien und Videos ohne entsprechende Berechtigung ist nicht gestattet.",
      },
      {
        heading: "Verwendung von Hochzeitsfotografien und Videos",
        body: "Die auf unserer Website gezeigten Hochzeitsfotografien und Videos dienen der Präsentation unserer fotografischen und filmischen Arbeit.\n\nDie Veröffentlichung von Aufnahmen, auf denen Personen erkennbar sind, erfolgt unter Beachtung der jeweils geltenden datenschutz- und persönlichkeitsrechtlichen Bestimmungen sowie der erforderlichen Einwilligungen beziehungsweise sonstigen Rechtsgrundlagen.\n\nDie Rechte an Kundenbildern und Kundenfilmen richten sich nach den jeweils getroffenen Vereinbarungen und den geltenden gesetzlichen Bestimmungen.\n\nBei Fragen zu veröffentlichten Inhalten oder Hinweisen auf mögliche Rechtsverletzungen kontaktieren Sie uns bitte unter:\n\n{email}",
      },
    ],
    note: "Stand: November 2026",
  },

  datenschutz: {
    title: "Datenschutzerklärung",
    sections: [
      {
        heading: "1. Verantwortlicher",
        body: "Verantwortlich für die Verarbeitung personenbezogener Daten auf dieser Website ist:\n\n**{owner} – {legalName}**\n\n{street}\n{zip} {city}\nDeutschland\n\nTelefon: {phone}\nE-Mail: {email}",
      },
      {
        heading: "2. Allgemeine Hinweise zur Datenverarbeitung",
        body: "Der Schutz Ihrer personenbezogenen Daten ist uns wichtig.\n\nWir verarbeiten personenbezogene Daten ausschließlich im Rahmen der geltenden gesetzlichen Datenschutzbestimmungen, insbesondere der Datenschutz-Grundverordnung (DSGVO) und des Telekommunikation-Digitale-Dienste-Datenschutz-Gesetzes (TDDDG).\n\nPersonenbezogene Daten sind alle Informationen, mit denen eine Person direkt oder indirekt identifiziert werden kann.\n\nDie Verarbeitung erfolgt insbesondere zur Bereitstellung unserer Website, zur Bearbeitung von Anfragen, zur Durchführung vorvertraglicher Maßnahmen sowie zur Erbringung unserer Foto- und Videodienstleistungen.",
      },
      {
        heading: "3. Hosting durch ALL-INKL",
        body: "Unsere Website wird bei folgendem Anbieter gehostet:\n\nALL-INKL.COM – Neue Medien Münnich\nInhaber: René Münnich\nHauptstraße 68\n02742 Friedersdorf\nDeutschland\n\nWebsite: [all-inkl.com](https://all-inkl.com/)\n\nBeim Besuch unserer Website werden durch den Hosting-Anbieter technisch erforderliche Informationen verarbeitet. Dazu können insbesondere folgende Daten gehören:\n\n- IP-Adresse des anfragenden Geräts\n- Datum und Uhrzeit des Zugriffs\n- aufgerufene Seiten und Dateien\n- übertragene Datenmenge\n- Browsertyp und Browserversion\n- verwendetes Betriebssystem\n- Referrer-URL\n- technische Statusmeldungen\n\nDie Verarbeitung erfolgt zur technischen Bereitstellung, Stabilität und Sicherheit unserer Website.\n\nRechtsgrundlage ist Artikel 6 Absatz 1 Buchstabe f DSGVO, soweit die Verarbeitung zur Wahrung unserer berechtigten Interessen erforderlich ist.\n\nSoweit ALL-INKL personenbezogene Daten in unserem Auftrag verarbeitet, erfolgt dies auf Grundlage einer Vereinbarung zur Auftragsverarbeitung gemäß Artikel 28 DSGVO.\n\nWeitere Informationen zum Datenschutz bei ALL-INKL: [all-inkl.com/datenschutzinformationen](https://all-inkl.com/datenschutzinformationen/)",
      },
      {
        heading: "4. Kontaktaufnahme und Kontaktformular",
        body: "Auf unserer Website befindet sich ein selbst programmiertes Kontaktformular.\n\nWenn Sie uns über dieses Formular kontaktieren, verarbeiten wir die von Ihnen eingegebenen personenbezogenen Daten zur Bearbeitung Ihrer Anfrage.\n\nDabei können insbesondere folgende Angaben verarbeitet werden:\n\n- Vor- und Nachname\n- E-Mail-Adresse\n- Telefonnummer\n- Hochzeitsdatum\n- Veranstaltungsort\n- gewünschte Dienstleistungen\n- individuelle Nachricht\n\nDie Verarbeitung erfolgt zur Beantwortung Ihrer Anfrage sowie gegebenenfalls zur Vorbereitung oder Durchführung eines Vertrags.\n\nRechtsgrundlagen:\n\n- Artikel 6 Absatz 1 Buchstabe b DSGVO bei vorvertraglichen Maßnahmen und Vertragsdurchführung.\n- Artikel 6 Absatz 1 Buchstabe f DSGVO bei sonstigen Anfragen, sofern die gesetzlichen Voraussetzungen erfüllt sind.\n\nDie übermittelten Daten werden gelöscht, sobald sie für die Bearbeitung der Anfrage nicht mehr erforderlich sind und keine gesetzlichen Aufbewahrungspflichten entgegenstehen.",
      },
      {
        heading: "5. Google Fonts",
        body: "Unsere Website verwendet Schriftarten von Google Fonts.\n\nDie Schriftarten werden derzeit von Google-Servern geladen. Beim Aufruf einer Seite, auf der diese Schriftarten eingebunden sind, kann eine Verbindung zu Google hergestellt werden.\n\nDabei kann insbesondere die IP-Adresse des Besuchers an Google übermittelt werden. Weitere technische Informationen können abhängig von der Einbindung verarbeitet werden.\n\nDie datenschutzrechtliche Zulässigkeit dieser Einbindung wird unter Berücksichtigung der geltenden Datenschutzbestimmungen geprüft.\n\nEine lokale Einbindung der Schriftarten auf unserem eigenen Server kann eine Übermittlung der IP-Adresse an Google beim Abruf der Schriftdateien vermeiden.\n\nWeitere Informationen zum Datenschutz bei Google: [policies.google.com/privacy](https://policies.google.com/privacy)",
      },
      {
        heading: "6. Google Maps",
        body: "Auf unserer Website verwenden wir Google Maps zur Darstellung von Karten und zur geografischen Orientierung.\n\nAnbieter ist Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland.\n\nBei einer direkten Einbindung von Google Maps kann bereits beim Laden der entsprechenden Website eine Verbindung zu Google hergestellt werden.\n\nDabei können insbesondere folgende Informationen verarbeitet werden:\n\n- IP-Adresse\n- technische Geräteinformationen\n- Browserinformationen\n- Nutzungsdaten\n- gegebenenfalls Standortinformationen\n\nSoweit eine Einwilligung erforderlich ist, wird die Karte erst nach entsprechender Zustimmung über unser Einwilligungsbanner geladen.\n\nRechtsgrundlage ist Artikel 6 Absatz 1 Buchstabe a DSGVO in Verbindung mit § 25 Absatz 1 TDDDG, soweit die jeweiligen Voraussetzungen vorliegen.",
      },
      {
        heading: "7. YouTube",
        body: "Unsere Website enthält Verlinkungen zu Videos und Inhalten auf YouTube.\n\nAnbieter ist Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland.\n\nWenn Sie einen YouTube-Link anklicken, werden Sie auf die externe Plattform weitergeleitet. Erst durch den Aufruf der externen Website erfolgt die dortige Datenverarbeitung durch den jeweiligen Anbieter.\n\nBei einer bloßen Verlinkung wird das YouTube-Video nicht automatisch auf unserer Website geladen.",
      },
      {
        heading: "8. Selbst entwickelte Onlinegalerie für Hochzeitsfotos und Videos",
        body: "{legalName} stellt seinen Kunden eine selbst entwickelte Onlinegalerie zur Verfügung, über die Hochzeitsfotografien und gegebenenfalls Videos angesehen und bereitgestellt werden können.\n\nDie Onlinegalerie wurde von {legalName} selbst programmiert und wird von uns verwaltet.\n\nIm Rahmen der Nutzung können folgende personenbezogene Daten verarbeitet werden:\n\n- Hochzeitsfotografien und Videodateien\n- Namen, sofern diese in der Galerie verwendet werden\n- individuelle Galerie-Links und Zugangsdaten\n- technische Zugriffsdaten\n- IP-Adressen und Zeitpunkte der Zugriffe, soweit diese technisch protokolliert werden\n\nDie Verarbeitung erfolgt zur Bereitstellung der vereinbarten Foto- und Videodienstleistungen.\n\nRechtsgrundlage ist Artikel 6 Absatz 1 Buchstabe b DSGVO, soweit die Verarbeitung zur Vertragserfüllung erforderlich ist. Für darüber hinausgehende Verarbeitungen ist gegebenenfalls eine gesonderte Rechtsgrundlage erforderlich.\n\nDer Zugang zu den Galerien kann durch individuelle Links oder weitere Zugangsbeschränkungen geregelt werden.\n\nDie gespeicherten Fotografien und Videos werden entsprechend den vertraglichen Vereinbarungen und den jeweiligen Speicherfristen aufbewahrt und anschließend gelöscht, sofern keine gesetzlichen Aufbewahrungspflichten oder sonstigen rechtmäßigen Gründe für eine weitere Speicherung bestehen.\n\nDie technische Bereitstellung und Speicherung erfolgt über die eingesetzte Hosting-Infrastruktur. Soweit hierfür ALL-INKL genutzt wird, gelten ergänzend die Angaben zum Hosting in Abschnitt 3.",
      },
      {
        heading: "9. Cookies und Einwilligungsverwaltung",
        body: "Unsere Website verfügt über ein selbst programmiertes Cookie- und Einwilligungsbanner.\n\nÜber die bereitgestellten Datenschutzeinstellungen können Besucher ihre Einwilligungsentscheidungen verwalten und jederzeit ändern oder widerrufen, soweit die jeweilige Funktion dies ermöglicht.\n\nCookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden können. Sie können beispielsweise zur Bereitstellung technischer Funktionen, zur Speicherung von Einstellungen oder zur statistischen Auswertung verwendet werden.\n\nTechnisch nicht erforderliche Cookies und vergleichbare Technologien, für die eine Einwilligung erforderlich ist, werden erst nach einer entsprechenden Einwilligung aktiviert.\n\nDie Rechtsgrundlage richtet sich nach dem jeweiligen Verarbeitungszweck. Insbesondere ist § 25 TDDDG für das Speichern von Informationen auf Endgeräten und den Zugriff auf bereits gespeicherte Informationen zu beachten.\n\n{{consent}}",
      },
      {
        heading: "10. Google Analytics",
        body: "Wir beabsichtigen, auf unserer Website Google Analytics einzusetzen.\n\nGoogle Analytics ist ein Webanalysedienst von Google, mit dem die Nutzung unserer Website statistisch ausgewertet werden kann.\n\nAnbieter ist Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland. Je nach Konfiguration können weitere Google-Unternehmen an der Verarbeitung beteiligt sein.\n\nGoogle Analytics kann insbesondere folgende Informationen verarbeiten:\n\n- aufgerufene Seiten\n- Seitenaufrufe und Interaktionen\n- Verweildauer\n- Browser- und Geräteinformationen\n- ungefähre Standortinformationen\n- technische Nutzungsdaten\n- Online-Kennungen und gegebenenfalls Cookie-Informationen\n\n**Zweck der Verarbeitung:** Statistische Auswertung der Website-Nutzung sowie Verbesserung der Inhalte, Funktionen und Benutzerfreundlichkeit unserer Website.\n\n**Rechtsgrundlage:** Artikel 6 Absatz 1 Buchstabe a DSGVO in Verbindung mit § 25 Absatz 1 TDDDG, soweit eine Einwilligung für das Speichern von Informationen auf dem Endgerät oder den Zugriff darauf erforderlich ist.\n\nGoogle Analytics wird erst nach Erteilung der erforderlichen Einwilligung über unser Cookie- und Einwilligungsbanner aktiviert.\n\nDie Einwilligung kann jederzeit mit Wirkung für die Zukunft über die Datenschutzeinstellungen unserer Website widerrufen werden.\n\nJe nach technischer Konfiguration können Daten an weitere Google-Unternehmen und gegebenenfalls in Drittländer, insbesondere die USA, übermittelt werden. Hierfür müssen die geltenden datenschutzrechtlichen Voraussetzungen und geeigneten Schutzmaßnahmen eingehalten werden.\n\nDie konkrete Speicherdauer richtet sich nach den Einstellungen des tatsächlich verwendeten Google-Analytics-Dienstes.\n\n**Hinweis zum aktuellen Stand:** Google Analytics ist zum Zeitpunkt der Erstellung dieser Datenschutzerklärung noch nicht aktiviert. Die technische Einrichtung und die Einbindung in unser Einwilligungsbanner erfolgen zu einem späteren Zeitpunkt. Vor der Aktivierung werden die tatsächlichen Einstellungen und Datenverarbeitungen geprüft und diese Datenschutzerklärung gegebenenfalls angepasst.",
      },
      {
        heading: "11. Weitergabe personenbezogener Daten",
        body: "Eine Weitergabe personenbezogener Daten an Dritte erfolgt nur, wenn dies:\n\n- zur Erfüllung eines Vertrags erforderlich ist,\n- zur Bearbeitung einer Anfrage notwendig ist,\n- aufgrund einer gesetzlichen Verpflichtung erforderlich ist,\n- auf einer wirksamen Einwilligung beruht oder\n- auf einer sonstigen gesetzlichen Rechtsgrundlage zulässig ist.\n\nTechnische Dienstleister können personenbezogene Daten im Rahmen ihrer jeweiligen Aufgaben verarbeiten. Soweit erforderlich, werden entsprechende Vereinbarungen zur Auftragsverarbeitung abgeschlossen.",
      },
      {
        heading: "12. Speicherdauer",
        body: "Personenbezogene Daten werden nur so lange gespeichert, wie dies für den jeweiligen Verarbeitungszweck erforderlich ist oder gesetzliche Aufbewahrungspflichten bestehen.\n\nNach Wegfall des jeweiligen Zwecks werden die Daten gelöscht, sofern keine gesetzlichen oder sonstigen rechtmäßigen Gründe für eine weitere Speicherung bestehen.",
      },
      {
        heading: "13. Rechte betroffener Personen",
        body: "Sie haben im Rahmen der gesetzlichen Voraussetzungen insbesondere folgende Rechte:\n\n- **Auskunft:** Recht auf Auskunft über Ihre gespeicherten personenbezogenen Daten gemäß Artikel 15 DSGVO.\n- **Berichtigung:** Recht auf Berichtigung unrichtiger Daten gemäß Artikel 16 DSGVO.\n- **Löschung:** Recht auf Löschung Ihrer personenbezogenen Daten gemäß Artikel 17 DSGVO.\n- **Einschränkung:** Recht auf Einschränkung der Verarbeitung gemäß Artikel 18 DSGVO.\n- **Datenübertragbarkeit:** Recht auf Datenübertragbarkeit gemäß Artikel 20 DSGVO.\n- **Widerspruch:** Recht auf Widerspruch gegen bestimmte Verarbeitungen gemäß Artikel 21 DSGVO.\n- **Widerruf:** Recht auf Widerruf einer erteilten Einwilligung mit Wirkung für die Zukunft gemäß Artikel 7 Absatz 3 DSGVO.\n\nZur Ausübung Ihrer Rechte können Sie uns jederzeit unter den oben genannten Kontaktdaten erreichen.",
      },
      {
        heading: "14. Beschwerderecht bei einer Aufsichtsbehörde",
        body: "Sie haben das Recht, sich bei einer Datenschutzaufsichtsbehörde zu beschweren, wenn Sie der Ansicht sind, dass die Verarbeitung Ihrer personenbezogenen Daten gegen geltendes Datenschutzrecht verstößt.\n\nFür Bayern ist insbesondere das Bayerische Landesamt für Datenschutzaufsicht zuständig, soweit es sich um eine Angelegenheit innerhalb seines Zuständigkeitsbereichs handelt.\n\nWebsite: [lda.bayern.de](https://www.lda.bayern.de/)",
      },
      {
        heading: "15. Datensicherheit",
        body: "Wir setzen angemessene technische und organisatorische Maßnahmen ein, um personenbezogene Daten vor Verlust, Manipulation und unberechtigtem Zugriff zu schützen.\n\nDie Kommunikation mit unserer Website erfolgt über eine verschlüsselte Verbindung, sofern die jeweilige Verbindung über HTTPS hergestellt wird.\n\nWir weisen darauf hin, dass eine vollständige Sicherheit bei der Datenübertragung im Internet nicht garantiert werden kann.",
      },
      {
        heading: "16. Aktualität und Änderungen",
        body: "Wir behalten uns vor, diese Datenschutzerklärung anzupassen, wenn sich die technischen Funktionen unserer Website, die eingesetzten Dienste oder die gesetzlichen Datenschutzanforderungen ändern.\n\nDie jeweils aktuelle Fassung wird auf unserer Website veröffentlicht.",
      },
    ],
    note: "Stand: November 2026",
  },

  agb: {
    title: "Allgemeine Geschäftsbedingungen",
    sections: [
      {
        heading: "§ 1 Geltungsbereich",
        body: "Diese Bedingungen gelten für alle Foto- und Filmaufträge sowie für digitale Produkte (Online-Galerie, digitale Hochzeitseinladung) von {legalName}, {street}, {zip} {city}.",
      },
      {
        heading: "§ 2 Vertragsschluss und Reservierung",
        body: "Der Vertrag kommt mit schriftlicher Auftragsbestätigung zustande. Der Termin gilt erst mit Eingang der Anzahlung in Höhe von 30 % der Auftragssumme als reserviert. Die Restzahlung ist spätestens 14 Tage nach der Veranstaltung fällig.",
      },
      {
        heading: "§ 3 Leistungsumfang",
        body: "Der Umfang ergibt sich aus dem gebuchten Paket. Die Auswahl und Bearbeitung der Bilder erfolgt nach künstlerischem Ermessen der Fotografin bzw. des Fotografen. Ein Anspruch auf unbearbeitete Rohdaten besteht nicht.",
      },
      {
        heading: "§ 4 Liefer- und Bearbeitungszeiten",
        body: "- Vorschau mit rund 20 Bildern innerhalb von 48 Stunden\n- Vollständige Online-Galerie innerhalb von drei Wochen\n- Hochzeitsfilm innerhalb von sechs Wochen",
      },
      {
        heading: "§ 5 Nutzungsrechte",
        body: "Die Auftraggeber erhalten ein einfaches, zeitlich und räumlich unbeschränktes Nutzungsrecht für private Zwecke einschließlich der Veröffentlichung in sozialen Netzwerken. Eine kommerzielle Nutzung oder Weitergabe an Dritte zu gewerblichen Zwecken bedarf einer gesonderten Vereinbarung. Das Urheberrecht verbleibt bei der Urheberin bzw. dem Urheber.",
      },
      {
        heading: "§ 6 Veröffentlichung durch uns",
        body: "Eine Veröffentlichung der Aufnahmen zu Referenzzwecken erfolgt ausschließlich nach gesonderter schriftlicher Einwilligung der abgebildeten Personen. Die Einwilligung ist freiwillig und jederzeit widerrufbar.",
      },
      {
        heading: "§ 7 Rücktritt und Ausfall",
        body: "Bei Rücktritt der Auftraggeber bis 180 Tage vor dem Termin wird die Anzahlung einbehalten. Bei späterem Rücktritt werden 50 % der Auftragssumme fällig, ab 30 Tagen vor dem Termin 80 %. Bei Krankheit oder höherer Gewalt auf unserer Seite stellen wir eine gleichwertige Vertretung aus unserem Netzwerk; ist dies nicht möglich, werden bereits geleistete Zahlungen vollständig erstattet. Weitergehende Ansprüche sind ausgeschlossen.",
      },
      {
        heading: "§ 8 Online-Galerie",
        body: "Die private Galerie ist passwortgeschützt und steht für 24 Monate zur Verfügung. Die Weitergabe der Zugangsdaten an Familie und Gäste ist gestattet, eine öffentliche Verbreitung des Links nicht.",
      },
      {
        heading: "§ 9 Digitale Hochzeitseinladung",
        body: "Die digitale Einladung ist für Kundinnen und Kunden mit gebuchtem Foto- oder Filmpaket kostenfrei. Für alle übrigen Nutzer beträgt der Preis einmalig 79 € inkl. MwSt.; die Einladungsseite bleibt zwölf Monate nach dem Hochzeitsdatum erreichbar. Für die eingegebenen Inhalte sind die erstellenden Personen verantwortlich. Es besteht kein Anspruch auf ununterbrochene Verfügbarkeit; wir bemühen uns um eine Erreichbarkeit von 99 % im Jahresmittel.",
      },
      {
        heading: "§ 10 Widerrufsrecht",
        body: "Verbraucher haben bei Fernabsatzverträgen ein 14-tägiges Widerrufsrecht. Bei Dienstleistungen zu einem bestimmten Termin (Veranstaltungsfotografie, § 312g Abs. 2 Nr. 9 BGB) besteht kein Widerrufsrecht. Bei digitalen Inhalten erlischt das Widerrufsrecht mit ausdrücklicher Zustimmung zum vorzeitigen Beginn der Ausführung.",
      },
      {
        heading: "§ 11 Schlussbestimmungen",
        body: "Es gilt deutsches Recht. Sollten einzelne Bestimmungen unwirksam sein, bleibt die Wirksamkeit der übrigen Bestimmungen unberührt.",
      },
    ],
    note: "**Hinweis:** Vorlage für die Demo-Version. Vor dem Livegang anwaltlich prüfen lassen.",
  },
};
