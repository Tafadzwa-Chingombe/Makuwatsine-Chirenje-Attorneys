<?php
/**
 * Static firm content, taken from the MC Attorneys company profile (2025).
 */

const FIRM = [
    'name'    => 'Makuwatsine Chirenje Attorneys',
    'short'   => 'MC Attorneys',
    'address' => ['6 Londonderry Avenue', 'Eastlea', 'Harare'],
    'phones'  => ['+263 24 2720094'],
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
            'name' => 'Tendai Makuwatsine', 'role' => 'Founding & Managing Partner', 'degree' => 'LLB (Hons), University of South Africa (UNISA)',
            'phones' => ['+263 773 753 866'],
            'bio' => "Tendai Makuwatsine is the Founding and Managing Partner of Makuwatsine Chirenje Attorneys. He obtained his Bachelor of Laws (Honours) degree from the University of South Africa in 2016 and has practised law at several established law firms before co-founding Makuwatsine Chirenje Attorneys with Liberty Chirenje in 2025.\nTendai's practice has a strong focus on conveyancing and property law, with experience in property transfers, mortgage bond registrations and other Deeds Office transactions. He has also been involved in title creation for land development projects across Zimbabwe, providing legal support in property-related transactions and development matters.\nHis broader areas of practice include notarial practice, deceased estates administration, mining law, labour law, insolvency law, debt collection, commercial law, family law, civil litigation and intellectual property.\nAs Managing Partner, Tendai is committed to maintaining high standards of professionalism, integrity and client service. He approaches legal matters with diligence and a practical understanding of the legal and commercial considerations affecting his clients.\nOutside his legal practice, Tendai is an avid golfer.",
        ],
        [
            'name' => 'Liberty Chirenje', 'role' => 'Founding and Senior Partner', 'degree' => 'LLB (Hons), University of Zimbabwe (UZ)',
            'phones' => ['+263 773 283 083', '+263 715 775 202'],
            'bio' => "Liberty Chirenje is a Founding and Senior Partner of Makuwatsine Chirenje Attorneys, bringing a wealth of legal experience and a broad-based understanding of litigation and legal practice. He obtained his Bachelor of Laws (Honours) degree from the University of Zimbabwe in 2015 and was admitted to practise law on 17 February 2016.\nLiberty began his legal career at Makuku Law Firm in February 2016, where he served as an Associate until December 2022. During this period, he played a key role in developing the firm's criminal law practice, handling criminal matters, including police trials. His work provided him with extensive practical experience in criminal litigation, while also allowing him to develop experience in conveyancing and notarial practice.\nOver time, Liberty broadened his practice to include civil litigation, deceased estates, mining law and commercial law. In 2023, he joined Macharaga Law Chambers as an Associate, where he continued to develop his professional experience until April 2025.\nIn April 2025, Liberty joined forces with Tendai Makuwatsine to establish Makuwatsine Chirenje Attorneys. As a Founding Partner, he contributes to the firm's strategic direction and its commitment to providing professional, practical and effective legal services.\nHis areas of practice include criminal litigation, civil litigation, conveyancing, notarial practice and commercial law. Liberty approaches his work with diligence, professionalism and a commitment to protecting his clients' interests.",
        ],
        [
            'name' => 'Taurayi Cyril Chirinda', 'role' => 'Senior Associate', 'degree' => 'LLB, University of South Africa',
            'phones' => ['+263 774 623 523'],
            'bio' => "Taurayi Cyril Chirinda a committed and experienced legal practitioner based in Harare. His drive stems from a strong desire to serve justice and make a positive impact in the legal field. Taurayi holds an LLB degree from University of South Africa specializing in both civil and criminal litigation, with specific interests in family law, immigration law, medical law, mining law, commercial law, media law, and intellectual property.",
        ],
        [
            'name' => 'Manasa Masiiwa', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) MSU, Zimbabwe',
            'phones' => ['+263 783 840 299', '+263 771 454 698'],
            'bio' => "After graduating with Midlands State University law school in December 2013, he briefly joined Mhaka Attorneys in 2014. In January 2015 he joined Judicial Service Commission as a Judge’s Assistant stationed at Bulawayo High Court. In June 2015 he switched from being a judge’s Assistant to be a Magistrate and worked as such till the year 2022 when he resigned and joined private practice first with Chitere Chidawanyika and partners in 2022, then T.K Takaindisa Law Chambers in 2023 up to 2025 March. On the 1st of April he joined Makuwatsine Chirenje Attorneys as a pioneer of the firm when it was starting",
        ],
        [
            'name' => 'Alexis Murombo', 'role' => 'Senior Associate', 'degree' => 'LLBS. UZ',
            'phones' => ['+263 772 410 736'],
            'bio' => "Alexio is having a second bite of his professional careers. He has spent more than 30 years, in the accounting and financial fields, having had long stints, with accounting firms such Ernst & Young, Alexander Katz etc. He is therefore, a seasoned professional in corporate accounting, administration and finance and the legal qualification broadens and a cherry to his professional depth.\nHaving peaked, to the positions of financial and commercial director, in the corporate sector, he has deep practical insights in all matters pertaining to corporate work within the ambit of commercial and corporate law as well as taxation. He was registered as a legal practitioner in March 2017 after enrolling with the University of Zimbabwe Faculty of Law to study law at the age of fifty-one.\nHis work with legal firms includes, Gula Ndebele & Partners, Matsikidze Attorneys at Law, Masinire Law Chambers and now with current law firm MC Attorneys.\nWhen all is said and done his matured and diligent approach to the legal profession cannot be over emphasised but rather appreciated.",
        ],
        [
            'name' => 'Tatenda Freddy Chimbadzwa', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) UNIVEN, South Africa',
            'phones' => ['+263 774 793 639'],
            'bio' => "He attained his LLB (Hons) Degree with University of Venda (South Africa) in 2014. In 2019 he was registered and admitted as a legal practitioner under the employee of Coghlan Welsh and Guests Legal Practitioner. In 2020, he joined Tadiwa and Associates Legal Practitioners were he practiced for 4 and half years under them. In July 2025, he joined Makuwatsine Chirenje Attorneys.\nMr Chimbadzwa’s academic pursuits have equipped him with a deep understanding of complex legal issues and the skills necessary to navigate now various issues. Since 2019 he acquired vast knowledge and experience from and Mr Chimbadwza expertise in criminal litigation, civil litigation, Administration of Estates, mining law, commercial law, Labour Law and Alternative Dispute Resolution.",
        ],
        [
            'name' => 'Takawira Mugabe', 'role' => 'Senior Associate', 'degree' => 'LLB, University of Zimbabwe',
            'phones' => ['+263 773 596 002'],
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
        ['title' => 'Family Law', 'intro' => 'We are dedicated to providing comprehensive and empathetic legal services for the diverse legal issues that arise within the family, guiding you in a manner that recognises the sensitivity of most such issues. Our team has the experience to offer services and support in relation to', 'items' => [
            'divorce and separation',
            'child custody, access, guardianship',
            'division of the matrimonial estate to ensure a fair settlement while minimising emotional strain',
            'maintenance, wills, trusts, and estate planning',
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
