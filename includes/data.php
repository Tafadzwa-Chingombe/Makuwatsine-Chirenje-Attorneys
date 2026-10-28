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
            'name' => 'Tendai Makuwatsine', 'role' => 'Managing Partner', 'degree' => 'LLB (Hons) SA',
            'bio' => "He is the founding partner who attained his Bachelor of Laws (Hons) Degree with University of South Africa in 2016. Prior to completing his degree, he has been Conveyancing Paralegal with Honey & Blankenberg Legal Practitioners from 2009 to 2016. He was promoted to an associate in 2016 and left the firm in August 2018 to join Chinawa Law Chambers as an Associate where he briefly stayed till December 2018. In January 2019 he joined J.Mambara & Partners as an Associate and left the firm in December 2021 to join Macharaga Law Chambers in January 2022 as an Associate Partner until March 2025. On 1 April 2025 he joined forces with Liberty Chirenje became a Founding Partner of Makuwatsine Chirenje Attorneys.\nDuring his years with various law firms, his practice was mainly Conveyancing and has over 16 years handling Conveyancing matters such as property transfers, creation of title from land developments, registration and cancellation of mortgage bonds etc . After he was registered to practice law his area of practice is Conveyancing, administration of estates, insolvency law, Notarial practice, debt collection, commercial law, general civil litigation and Intellectual property.\nMr Makuwatsine is well respected in the Conveyancing practice and has many Estate agent’s companies as his clients. He is currently involved in various land development projects around the country. He has a passion for property law and is presently specialising in that branch of law.",
        ],
        [
            'name' => 'Liberty Chirenje', 'role' => 'Founding Partner', 'degree' => 'LLB (Hons) UZ',
            'bio' => 'Admitted to practice in February 2016, with deep experience in criminal litigation and police trials. A notary public and conveyancer whose practice also spans civil litigation, administration of estates, mining and commercial law. Practice areas: criminal, conveyancing, notarial practice, civil litigation and commercial law.',
        ],
        [
            'name' => 'Taurayi Cyril Chirinda', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) SA',
            'bio' => 'Specialises in civil and criminal litigation, with interests in family, immigration, medical, mining, commercial, media and intellectual property law.',
        ],
        [
            'name' => 'Manasa Masiiwa', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) MSU',
            'bio' => 'Former Judge’s Assistant and Magistrate (2015–2022), bringing bench experience to private practice since joining as a pioneer of the firm.',
        ],
        [
            'name' => 'Alexis Murombo', 'role' => 'Senior Associate', 'degree' => 'LLBS. UZ',
            'bio' => "Alexio is having a second bite of his professional careers. He has spent more than 30 years, in the accounting and financial fields, having had long stints, with accounting firms such Ernst & Young, Alexander Katz etc. He is therefore, a seasoned professional in corporate accounting, administration and finance and the legal qualification broadens and a cherry to his professional depth.\nHaving peaked, to the positions of financial and commercial director, in the corporate sector, he has deep practical insights in all matters pertaining to corporate work within the ambit of commercial and corporate law as well as taxation. He was registered as a legal practitioner in March 2017 after enrolling with the University of Zimbabwe Faculty of Law to study law at the age of fifty-one.\nHis work with legal firms includes, Gula Ndebele & Partners, Matsikidze Attorneys at Law, Masinire Law Chambers and now with current law firm MC Attorneys.\nWhen all is said and done his matured and diligent approach to the legal profession cannot be over emphasised but rather appreciated.",
        ],
        [
            'name' => 'Tatenda Freddy Chimbadzwa', 'role' => 'Legal Practitioner', 'degree' => 'LLB (Hons) UNIVEN, South Africa',
            'bio' => "He attained his LLB (Hons) Degree with University of Venda (South Africa) in 2014. In 2019 he was registered and admitted as a legal practitioner under the employee of Coghlan Welsh and Guests Legal Practitioner. In 2020, he joined Tadiwa and Associates Legal Practitioners were he practiced for 4 and half years under them. In July 2025, he joined Makuwatsine Chirenje Attorneys.\nMr Chimbadzwa’s academic pursuits have equipped him with a deep understanding of complex legal issues and the skills necessary to navigate now various issues. Since 2019 he acquired vast knowledge and experience from and Mr Chimbadwza expertise in criminal litigation, civil litigation, Administration of Estates, mining law, commercial law, Labour Law and Alternative Dispute Resolution.",
        ],
        [
            'name' => 'Takawira Mugabe', 'role' => 'Senior Associate', 'degree' => 'LLB (Hons) SA',
            'bio' => 'Specialises in civil and criminal litigation, with interests in family, immigration, medical, mining, commercial, media and intellectual property law.',
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
        ['title' => 'Criminal Law', 'intro' => 'We handle criminal law issues which may be faced by our corporate clients.'],
        ['title' => 'Energy and Mining Law', 'items' => [
            'Advice in respect of the legislative and regulatory requirements relating to energy and mining projects including environmental and safety issues',
            'Advice in respect of Zimbabwe Investment Authority applications, Special Economic Zones and immigration requirements',
            'Drafting of transaction documentation',
            'Drafting or reviewing project finance agreements',
            'Investment issues particularly pertinent to the mining sector',
            'Advice relating to and registration of security',
        ]],
        ['title' => 'Estate Planning, Wills and Successions', 'items' => [
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
        ['title' => 'Arbitration', 'items' => [
            'Recommend wording of dispute resolution clauses in arbitration agreements',
            'Service as arbitrators in commercial and labour disputes in which we have no interest',
            'Are named in divers lists of arbitrators maintained by various arbitration structures in Zimbabwe',
            'Advise in relation to the law and procedures of arbitration, including enforcement of international awards in Zimbabwe',
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
        ['title' => 'Tax Advice and Litigation', 'items' => [
            'Often working in conjunction with established accounting firms, we offer advice on a wide range of issues relating to income tax, various categories of withholding tax, and VAT',
            ['We provide advice in relation to tax litigation generally, and in particular with respect to:', [
                'Framing objections to tax assessments',
                'Filing appeals to the tax appeal courts in terms of the relevant provisions of the Income Tax Act and Value Added Tax Act',
                'Prosecuting tax appeals up to the stage of hearing',
                'Arranging representation for clients at the hearing of tax appeals',
            ]],
        ]],
    ];
}
