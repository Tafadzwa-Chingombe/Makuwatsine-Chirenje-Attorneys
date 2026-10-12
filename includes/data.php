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
            'bio' => 'Founding partner with over 16 years in conveyancing — property transfers, creation of title from land developments, and registration and cancellation of mortgage bonds. Well respected in the property sector, with many estate agent companies as clients. Practice areas: conveyancing, estates, insolvency, notarial practice, debt collection, commercial law, civil litigation and intellectual property.',
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
    ];
}
