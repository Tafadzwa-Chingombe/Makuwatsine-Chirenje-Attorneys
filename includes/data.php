<?php
/**
 * Static firm content, taken from the MC Attorneys company profile (2025).
 */

const FIRM = [
    'name'    => 'Makuwatsine Chirenje Attorneys',
    'short'   => 'MC Attorneys',
    'address' => ['6 Londonderry Avenue', 'Eastlea', 'Harare'],
    'phones'  => ['0773 753 866', '0773 283 083', '0719 623 523', '0771 454 698'],
    'email'   => 'makuwatsinechirenjelaw@gmail.com',
    'banker'  => 'CBZ Bank Limited, Selous Avenue, Harare',
];

function practice_areas(): array
{
    return [
        ['Litigation', 'Representation in criminal and civil matters in the courts of Zimbabwe, including police trials.'],
        ['Notarial Services', 'Notarial practice and the authentication of documents for use at home and abroad.'],
        ['Conveyancing', 'Property transfers, creation of title from land developments, registration and cancellation of mortgage bonds.'],
        ['Estates', 'Executors and administrators of deceased estates, from registration to final distribution.'],
        ['Labour Law', 'Consultancy and representation for employers and employees in labour disputes.'],
        ['Corporate Law', 'Company formation, governance and corporate advisory services.'],
        ['Advocate Services', 'Specialist advocacy and court representation across civil and criminal forums.'],
        ['Commercial Law', 'Commercial contracts, agreements and transactional advice for businesses.'],
        ['Company Liquidations', 'Insolvency and liquidation of companies, handled with diligence and transparency.'],
        ['Mining Law', 'Mining titles, claims, agreements and compliance for mining ventures.'],
        ['Debt Collection', 'Efficient recovery of debts through demand, negotiation and litigation.'],
        ['Patents & Trademarks', 'Registration and protection of intellectual property as patent and trademark agents.'],
    ];
}

function home_areas(): array
{
    $want = ['Conveyancing', 'Litigation', 'Estates', 'Commercial Law', 'Labour Law', 'Mining Law'];
    $short = [
        'Conveyancing' => 'Property transfers, creation of title, mortgage bonds and land development.',
        'Litigation' => 'Representation in criminal and civil matters before the courts.',
        'Estates' => 'Executors and administrators of deceased estates.',
        'Commercial Law' => 'Corporate, company liquidations, debt collection and contracts.',
        'Labour Law' => 'Advice and representation for employers and employees.',
        'Mining Law' => 'Claims, titles and compliance for mining ventures.',
    ];
    return array_map(fn($t) => [$t, $short[$t]], $want);
}

function team(): array
{
    return [
        [
            'name' => 'Tendai Makuwatsine', 'role' => 'Founding and Managing Partner', 'degree' => 'LLB (Hons) SA',
            'bio' => "He is the founding and managing partner of Makuwatsine Chirenje Attorneys. He attained his Bachelor of Laws (Hons) Degree from University of South Africa in 2016. Tendai has practiced law in a number of well-established law firms before founding this firm with Liberty Chirenje in 2025.\nHis specialty is in Conveyancing, he has extensive experience in Conveyancing matters such as registration of transfers and mortgage bonds with the Deeds Office. He is widely involved creating titles in land development and other conveyancing matters.\nHe is also a registered Legal Practitioners and Notary Public, his area of practice is Notarial Practice, administration of estates, mining law, labour law insolvency law, debt collection, commercial law, family law, general civil litigation and Intellectual property.\nIn his spare time he is an avid golfer.",
        ],
        [
            'name' => 'Liberty Chirenje', 'role' => 'Founding Partner', 'degree' => 'LLB (Hons) UZ, Zimbabwe',
            'bio' => "His introduction to the legal fraternity began with working for Makuku Law Firm in 2016 as an Associate. He started studying Law with University of Zimbabwe in 2010 and graduated in 2015 and admitted to practice on the 17th of February 2016.\nHe joined Makuku Law Firm in February 2016 to December 2022 as an Associate. Wherein he was tasked with making the criminal department visible. ln this regard he was solely responsible for handling criminal matters including police trials. During this period he acquired vast knowledge in criminal litigation and also did some notarial and conveyancing work and a notary public and conveyancer. He later on started to grew interest in other areas of practice and started to do civil litigation, Administration of Estates, mining law and commercial law.\nIn 2023 he joined Macharaga Law Chambers as an associate to until 1st April 2025 when he joined forces with Tendai Makuwatsine and became a Founding Partner of Makuwatsine Chirenje Attorneys. his area of practice includes Criminal, Conveyancing, Notarial Practice, Civil litigation and Commercial law.",
        ],
        [
            'name' => 'Taurayi Cyril Chirinda', 'role' => 'Senior Associate', 'degree' => 'LLB, University of South Africa',
            'bio' => "Taurayi Cyril Chirinda a committed and experienced legal practitioner based in Harare. His drive stems from a strong desire to serve justice and make a positive impact in the legal field. Taurayi holds an LLB degree from University of South Africa specializing in both civil and criminal litigation, with specific interests in family law, immigration law, medical law, mining law, commercial law, media law, and intellectual property.",
        ],
        [
            'name' => 'Manasa Masiiwa', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) MSU, Zimbabwe',
            'bio' => "After graduating with Midlands State University law school in December 2013, he briefly joined Mhaka Attorneys in 2014. In January 2015 he joined Judicial Service Commission as a Judge’s Assistant stationed at Bulawayo High Court. In June 2015 he switched from being a judge’s Assistant to be a Magistrate and worked as such till the year 2022 when he resigned and joined private practice first with Chitere Chidawanyika and partners in 2022, then T.K Takaindisa Law Chambers in 2023 up to 2025 March. On the 1st of April he joined Makuwatsine Chirenje Attorneys as a pioneer of the firm when it was starting",
        ],
        [
            'name' => 'Alexis Murombo', 'role' => 'Senior Associate', 'degree' => 'LLBS. UZ',
            'bio' => "Alexio is having a second bite of his professional careers. He has spent more than 30 years, in the accounting and financial fields, having had long stints, with accounting firms such Ernst & Young, Alexander Katz etc. He is therefore, a seasoned professional in corporate accounting, administration and finance and the legal qualification broadens and a cherry to his professional depth.\nHaving peaked, to the positions of financial and commercial director, in the corporate sector, he has deep practical insights in all matters pertaining to corporate work within the ambit of commercial and corporate law as well as taxation. He was registered as a legal practitioner in March 2017 after enrolling with the University of Zimbabwe Faculty of Law to study law at the age of fifty-one.\nHis work with legal firms includes, Gula Ndebele & Partners, Matsikidze Attorneys at Law, Masinire Law Chambers and now with current law firm MC Attorneys.\nWhen all is said and done his matured and diligent approach to the legal profession cannot be over emphasised but rather appreciated.",
        ],
        [
            'name' => 'Tatenda Freddy Chimbadzwa', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) UNIVEN, South Africa',
            'bio' => "He attained his LLB (Hons) Degree with University of Venda (South Africa) in 2014. In 2019 he was registered and admitted as a legal practitioner under the employee of Coghlan Welsh and Guests Legal Practitioner. In 2020, he joined Tadiwa and Associates Legal Practitioners were he practiced for 4 and half years under them. In July 2025, he joined Makuwatsine Chirenje Attorneys.\nMr Chimbadzwa’s academic pursuits have equipped him with a deep understanding of complex legal issues and the skills necessary to navigate now various issues. Since 2019 he acquired vast knowledge and experience from and Mr Chimbadwza expertise in criminal litigation, civil litigation, Administration of Estates, mining law, commercial law, Labour Law and Alternative Dispute Resolution.",
        ],
        [
            'name' => 'Takawira Mugabe', 'role' => 'Senior Associate', 'degree' => 'LLB, University of Zimbabwe',
            'bio' => "Takawira Mugabe graduated from the University of Zimbabwe with an LLB. He joined the Ministry of Justice as a Prosecutor for two years and then moved to the bench as a Magistrate. He left the Judicial Service Commission after 16 years and joined Tadiwa and Associates as a Legal Practitioner. After a year with Tadiwa and Associates he then joined Makuwatsine Chirenje Attorneys.\nAn astute lawyer with vast experience in criminal and civil law, and a human rights litigation lawyer too. Also specialised in International Trade law administration of estates.",
        ],
    ];
}


/** Detailed practice-area content for the Practice Areas page. Items may be a string or [text, [sub-items]]. */
function service_areas(): array
{
    return [
        ['title' => 'Conveyancing and Notarial Practice', 'items' => [
            'Registration and transfers of fixed property',
            'Registration of mortgage bonds over fixed property',
            'Registration of notarial bonds over moveable property',
            'Advice on tax matters involved in property transfers',
            'Advice on security registration generally',
            'All legal issues pertaining to fixed properties, such as usufructs, servitudes and related real rights',
            'Advice on share block schemes and sectional title schemes',
        ]],
        ['title' => 'Criminal Law', 'intro' => 'Our practitioners aim to protect the rights of the individual. We have a rights-based approach to criminal justice. Our team will assist clients with legal advice from the time of arrest to the finalization of the matter. We handle the following:', 'items' => [
            'Attendance at police stations and holding cells to ensure that client’s rights are not violated',
            'Bail applications',
            'Applications for the production of arrested persons by the state',
            'Criminal trial litigation in all courts including appeals and private prosecutions',
        ]],
        ['title' => 'Energy and Mining Law', 'items' => [
            'Advice in respect of the legislative and regulatory requirements relating to energy and mining projects including environmental and safety issues',
            'Advice in respect of Zimbabwe Investment Authority applications, Special Economic Zones and immigration requirements',
            'Drafting of transaction documentation',
            'Drafting or reviewing project finance agreements',
            'Investment issues particularly pertinent to the mining sector',
            'Advice relating to and registration of security',
        ]],
        ['title' => 'Estate Planning and Administration of Estates', 'items' => [
            'Estate planning with consideration to trusts, company structures, tax and estate duty',
            'Drafting of trusts',
            'Drafting of wills',
            'Winding up of estates.',
        ]],
        ['title' => 'General Civil Litigation', 'intro' => 'We service all areas of civil dispute resolution that our corporate or private clients may need assistance with.', 'items' => [
            'All manner of civil litigation arising from commercial transactions of every kind',
            'Debt collection work on behalf of commercial clients',
            'Applications for liquidation and judicial management, acting both on behalf of creditors and on behalf of the corporate entity itself',
            'Advice in relation to schemes of arrangement with creditors, drafting scheme documents, the necessary applications to court, and generally managing the process',
            'Settlement negotiations, mediations and other forms of alternative dispute resolution',
        ]],
        ['title' => 'Commercial and Corporate Law', 'items' => [
            'Investment and exchange control issues generally, but with specific reference to foreign direct investment',
            'Legal due diligence work generally, and with reference to proposed acquisitions in particular',
            'Advice in relation to Zimbabwe’s indigenisation laws and policy',
            'Guidance in relation to the various legal regimes applicable to particular fields of business activity in Zimbabwe, with particular reference to mining, banking, insurance and manufacturing',
            'Zimbabwe’s competition law',
            'Mergers, acquisitions and restructurings',
            'Advice in relation to Zimbabwe’s environmental laws',
            'Compliance with Zimbabwe’s Companies Act and subsidiary legislation',
            'Compliance with the Zimbabwe Stock Exchange Listing Requirements',
            'Drafting of all necessary commercial contracts',
        ]],
        ['title' => 'Insurance Law', 'items' => [
            'Reviewing insurance contracts',
            'Provision of regulatory advice',
            'Advising on insurance claims',
            'Insurance related litigation',
            'Advising on insurance legislation',
        ]],
        ['title' => 'Intellectual Property', 'intro' => 'Our department advises both local and international clients as to the general protection of intellectual property rights, collaborates with a variety of international IP practitioners in relation to the registration of IP rights regionally and internationally, and maintains a close relationship with the African Regional Intellectual Property Organisation (ARIPO).', 'items' => [
            'Trade Marks',
            'Patents / Utility Models (Petty Patents)',
            'Copyright',
            'Designs',
            'Domain names',
            'Plant Breeders’ Rights Registrations',
        ]],
        ['title' => 'Labour Law', 'items' => [
            'Advise on the labour law regime applicable in Zimbabwe in all its aspects, including minimum conditions of employment, unfair labour practices, the requirements for an employment contract, the applicability of collective bargaining agreements, codes of conduct, retrenchments and termination of employment',
            'Draft contracts of employment',
            'Manage retrenchment processes – both voluntary and compulsory',
            'Manage disciplinary proceedings',
            'Advise clients in relation to labour disputes',
            'Represent clients in labour arbitration or before the Labour Court and related appeals',
            'Draft confidentiality undertakings and agreements in restraint of trade.',
        ]],
        ['title' => 'Business Rescue and Insolvency', 'items' => [
            'Advice on various business rescue alternatives and their implication',
            'Advice in respect of the Insolvency Act, the Companies Act and related legislation',
            'Advice in relation to securities in insolvency',
            'Drafting and prosecution of court applications relating to judicial management, liquidation and insolvency and the opposition of hostile applications',
            'Advice relating to the liabilities resulting from judicial management, liquidation and insolvency',
            'Advice relating to employment issues arising from judicial management, liquidation and insolvency',
            'Advice on Schemes of Arrangement.',
        ]],
    ];
}
