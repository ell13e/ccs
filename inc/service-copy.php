<?php
/**
 * Service page copy (Nov 2026), from the client's copy pack
 * ("home_care_website_copy.md"), one entry per service: the page's heading,
 * opening paragraphs, its sections (a heading with paragraphs and items, an
 * item being a lead-in and a line, or a line alone) and its questions.
 *
 * Generated from the pack, with these changes only: the company name, area
 * and phone number filled in; dashes turned into commas (house style);
 * every line that mentions live-in care removed (the client does not provide
 * it); and paragraphs that quote another organisation word for word, or give
 * a funding figure, left out until someone has checked them against the
 * source. Edit the wording here.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Copy for one service, or an empty array.
 *
 * @param string $slug Service slug.
 * @return array
 */
function ccs_service_copy( $slug ) {
	static $copy = null;
	if ( null === $copy ) {
		$copy = array(
			'domiciliary-care' => array(
				'title' => 'Compassionate, Regulated Domiciliary Care',
				'intro' => array(
					'Domiciliary care simply means receiving professional care and support in your own home, rather than moving into a residential care home. At Continuity of Care Services, our visiting care workers deliver reliable, compassionate support that keeps you connected to your home, family, and community.',
				),
				'sections' => array(
					array(
						'heading' => 'Why Choose Domiciliary Care?',
						'paras' => array(),
						'items' => array(
							array(
								'Stay in familiar surroundings',
								'Keep your own home, garden, pets, and neighbourhood.',
							),
							array(
								'Flexible visiting schedules',
								'Pay only for the hours of support you actually need.',
							),
							array(
								'Regulated peace of mind',
								'As the CQC outlines, any agency providing personal care in a person’s own home in England is legally required to register with and be inspected by the Care Quality Commission.',
							),
						),
					),
					array(
						'heading' => 'How We Support You',
						'paras' => array(
							'From personal care and mobility assistance to companionship and domestic help, our fully vetted, trained care workers visit at pre-agreed times to support your wellbeing with warmth and professionalism.',
						),
						'items' => array(),
					),
				),
				'faqs' => array(
					array(
						'Is domiciliary care the same as home care?',
						'Yes. Domiciliary care is the formal term used by the NHS, local authorities, and the CQC for visiting home care.',
					),
					array(
						'Can domiciliary care be increased if my needs change?',
						'Absolutely. We review your care plan regularly and can scale up visits or transition to overnight or continuous care seamlessly.',
					),
				),
			),
			'respite-care' => array(
				'title' => 'Respite Care at Home: Supporting You While You Take a Break',
				'intro' => array(
					'Caring for a loved one is deeply rewarding, but it can also be exhausting. Taking time to rest isn’t selfish, it is essential for your own health and for the person you care for.',
				),
				'sections' => array(
					array(
						'heading' => 'Flexible Short-Term Support',
						'paras' => array(
							'Whether you need a few hours off each week, cover for a family holiday, or temporary support while you recover from illness, Continuity of Care Services steps in smoothly so your loved one can remain in their own home without the disruption of a temporary care home stay.',
						),
						'items' => array(
							array(
								'Regular weekly breaks',
								'Scheduled visits so you can attend appointments, work, or simply recharge.',
							),
							array(
								'Seamless handovers',
								'We learn your loved one’s exact routines, preferences, and likes before you step away.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'How much notice do you need for respite care?',
						'We recommend booking planned breaks as early as possible, though we can also arrange urgent respite cover at short notice.',
					),
					array(
						'Can I get help paying for respite care?',
						'You are entitled to a free carer’s assessment from your local council, which can identify funding or Direct Payments to help cover respite breaks.',
					),
				),
			),
			'companionship-care' => array(
				'title' => 'Companionship Care: Friendship, Conversation, and Connection',
				'intro' => array(
					'Good care is about much more than practical tasks, it is about emotional wellbeing, laughter, and staying connected to the world around you. Our companionship care matches you with a friendly, compatible care worker who shares your interests and brings warmth to your week.',
				),
				'sections' => array(
					array(
						'heading' => 'What Our Companions Can Help With',
						'paras' => array(),
						'items' => array(
							array(
								'Meaningful company',
								'A cup of tea, a good chat, board games, reading, or reminiscing.',
							),
							array(
								'Getting out and about',
								'Accompanied walks, coffee shop trips, garden centre visits, or social clubs.',
							),
							array(
								'Hobbies and technology',
								'Help with crafts, gardening, or video-calling family members.',
							),
							array(
								'Practical errands',
								'Accompanying you to GP appointments, hairdressers, or weekly food shopping.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'How do you match companions?',
						'During your free assessment, we learn about your personality, hobbies, and background so we can introduce someone you will genuinely enjoy spending time with.',
					),
					array(
						'Can companionship be combined with housework?',
						'Yes, visits can include a mix of social time, light housekeeping, and meal preparation.',
					),
				),
			),
			'overnight-care' => array(
				'title' => 'Safe, Reassuring Overnight Care at Home',
				'intro' => array(
					'Night-time can be the most anxious period for people with care needs and their families, whether due to mobility difficulties, confusion, medication schedules, or the fear of falling. Our overnight care service ensures skilled support is right there whenever it is needed.',
					'As Macmillan Cancer Support highlights, overnight care at home helps people remain safely in their own surroundings through the night while giving family carers uninterrupted, restorative rest.',
				),
				'sections' => array(
					array(
						'heading' => 'Two Types of Overnight Care',
						'paras' => array(),
						'items' => array(
							array(
								'Sleeping Night Care',
								'A care worker sleeps at your home and is on hand if you need occasional assistance (typically up to two brief wake-ups per night) for reassurance or help to the bathroom.',
							),
							array(
								'Waking Night Care',
								'A care worker remains awake and alert throughout the night, ideal for individuals who need regular repositioning, frequent personal care, time-critical medication, or supervision due to dementia or complex clinical needs.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'What do we need to provide for a sleeping night carer?',
						'Just a private, comfortable bed and access to bathroom facilities.',
					),
					array(
						'Can we book overnight care just a few nights a week?',
						'Yes. Overnight care can be arranged as a one-off, a few nights a week to give family carers a break, or every night.',
					),
				),
			),
			'continuous-care' => array(
				'title' => 'Continuous Care: Round-the-Clock Support in Your Own Home',
				'intro' => array(
					'Our continuous care service provides dedicated, one-to-one support in your own home, allowing you to maintain your routines, keep beloved pets, welcome visitors whenever you choose, and remain part of your local community.',
				),
				'sections' => array(
					array(
						'heading' => 'How Continuous Care Works',
						'paras' => array(),
						'items' => array(
							array(
								'24-hour rotational care',
								'For higher or complex needs requiring constant awake supervision, a small team of care workers operates on a structured day-and-night shift rota.',
							),
							array(
								'Couples care',
								'Continuous care is often more cost-effective, and far less disruptive, than two residential care home places, allowing couples to stay together at home.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'How does continuous care compare to a care home?',
						'Unlike a care home where staff divide their time between many residents, continuous care offers dedicated 1-to-1 attention built entirely around your personal timetable.',
					),
				),
			),
			'palliative-care' => array(
				'title' => 'Holistic, Dignified Palliative Care at Home',
				'intro' => array(
					'Being diagnosed with a serious or life-limiting illness brings emotional and practical challenges for the whole family. Our palliative care service is focused on comfort, dignity, and helping you live as fully and peacefully as possible in the familiarity of your own home.',
				),
				'sections' => array(
					array(
						'heading' => 'Working Together Around You',
						'paras' => array(
							'Following Macmillan best practice, our specially trained care workers collaborate closely with your GP, district nurses, occupational therapists, and local hospice teams to provide seamless wrap-around care:',
						),
						'items' => array(
							array(
								'',
								'Sensitive personal care and comfort positioning',
							),
							array(
								'',
								'Support with symptom management and medication routines',
							),
							array(
								'',
								'Emotional support and practical respite for family members',
							),
							array(
								'',
								'Calm, respectful end-of-life care when the time comes',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Is palliative care only for the final days of life?',
						'No. Palliative care can begin months or even years before the end of life to help manage symptoms and maintain quality of life.',
					),
					array(
						'Can palliative care be arranged quickly?',
						'Yes. We can fast-track assessments and coordinate directly with hospital discharge teams or NHS Fast Track Continuing Healthcare.',
					),
				),
			),
			'personal-care' => array(
				'title' => 'Personal Care Delivered With Dignity and Respect',
				'intro' => array(
					'Asking for help with intimate daily routines can feel daunting. At Continuity of Care Services, our care workers are trained to deliver personal care with warmth, discretion, and complete respect for your privacy and independence.',
				),
				'sections' => array(
					array(
						'heading' => 'How We Can Help',
						'paras' => array(),
						'items' => array(
							array(
								'',
								'Bathing, showering, bed baths, and personal grooming',
							),
							array(
								'',
								'Hair care, shaving, and oral hygiene',
							),
							array(
								'',
								'Getting dressed and undressed comfortably',
							),
							array(
								'',
								'Discreet continence and stoma care',
							),
							array(
								'',
								'Assistance with eating, drinking, and medication prompts',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Can I request a female or male care worker?',
						'Yes. Your comfort comes first, and we always respect your gender preferences for personal care.',
					),
					array(
						'Are your care workers trained in safe moving and handling?',
						'Yes. All staff complete accredited moving, handling, and hoist training before supporting clients.',
					),
				),
			),
			'elderly-care' => array(
				'title' => 'Empowering Elderly Care in the Comfort of Home',
				'intro' => array(
					'Growing older shouldn’t mean giving up your independence or leaving the home where your memories live. Our elderly care services are designed to support older adults to stay safe, active, and fulfilled on their own terms.',
				),
				'sections' => array(
					array(
						'heading' => 'Tailored to Your Stage of Life',
						'paras' => array(
							'We never take a one-size-fits-all approach. Whether you need a little help with heavier chores, mobility reassurance to prevent falls, or more comprehensive daily care, we build a plan that promotes your strengths:',
						),
						'items' => array(
							array(
								'Fall prevention and mobility support',
								'Safe transfers, home hazard awareness, and gentle encouragement to stay active.',
							),
							array(
								'Nutrition and wellbeing',
								'Freshly prepared meals, hydration reminders, and prescription management.',
							),
							array(
								'Social connection',
								'Companionship visits and support to stay involved in local community groups.',
							),
						),
					),
					array(
						'heading' => 'Know Your Rights to Support',
						'paras' => array(
							'Both Age UK and the NHS recommend starting with a free local authority care needs assessment to identify what practical support, home adaptations, or care visits will best support your independence. We can support you privately alongside council services or as your primary care provider.',
						),
						'items' => array(),
					),
				),
				'faqs' => array(
					array(
						'My parent is reluctant to accept help, how do we start?',
						'Many families start with light companionship or housekeeping visits once a week so trust builds naturally before introducing personal care.',
					),
					array(
						'Can family members stay updated on visits?',
						'Yes. With your loved one’s consent, we provide clear communication and regular care plan updates for complete peace of mind.',
					),
				),
			),
			'long-term-care' => array(
				'title' => 'Long-Term Care That Adapts With You',
				'intro' => array(
					'When you or a loved one need ongoing support over months or years, consistency and trust are everything. Our long-term care service pairs you with a dedicated team of care workers who get to know your life, preferences, and goals inside out.',
				),
				'sections' => array(
					array(
						'heading' => 'The Benefits of Long-Term Home Care',
						'paras' => array(),
						'items' => array(
							array(
								'Small, consistent care teams',
								'Familiar faces who notice subtle changes in health or mood early.',
							),
							array(
								'Seamless scaling',
								'Easily increase or decrease hours without changing providers.',
							),
							array(
								'Joined-up healthcare',
								'We liaise with your GP, pharmacist, and community health teams to keep your care coordinated.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'What happens if my needs become more complex over time?',
						'Because we offer everything from everyday visiting care to specialist condition-led and continuous care, you can stay with the same trusted provider.',
					),
					array(
						'How often is my care plan reviewed?',
						'We schedule formal reviews at regular intervals and can update your plan at any time at your request.',
					),
				),
			),
			'younger-adults-care' => array(
				'title' => 'Independent Living Support for Younger Adults',
				'intro' => array(
					'Care for working-age adults (18–65) should never feel clinical or restrictive. Whether you are living with a physical disability, a long-term health condition, a learning disability, or recovering from an injury, our support is built around your ambitions, autonomy, and lifestyle.',
				),
				'sections' => array(
					array(
						'heading' => 'Living Life on Your Terms',
						'paras' => array(
							'We focus on enabling you to achieve your personal goals, rather than simply completing tasks:',
						),
						'items' => array(
							array(
								'Education and employment',
								'Morning routines, travel support, and workplace or university assistance.',
							),
							array(
								'Social life and hobbies',
								'Going to gigs, the gym, sports, pubs, or visiting friends.',
							),
							array(
								'Independent living skills',
								'Budgeting, cooking, running a home, and managing appointments.',
							),
							array(
								'Personal and clinical care',
								'Discreet support tailored to your schedule.',
							),
						),
					),
					array(
						'heading' => 'Person-Centred & Positive Support',
						'paras' => array(
							'In line with CQC guidance on person-centred care, and Positive Behaviour Support (PBS) principles where relevant for autistic people or adults with a learning disability, we match you with enthusiastic, like-minded personal assistants who respect your privacy, choices, and right to independence.',
						),
						'items' => array(),
					),
				),
				'faqs' => array(
					array(
						'Can I use Direct Payments or a Personal Health Budget?',
						'Yes. Many of our younger adult clients fund their support through local authority Direct Payments, NHS Personal Health Budgets, or Access to Work.',
					),
					array(
						'Can I choose who supports me?',
						'Yes. We involve you directly in matching your support workers so you feel completely comfortable.',
					),
				),
			),
			'complex-care' => array(
				'title' => 'Clinically Led Complex Care in Your Own Home',
				'intro' => array(
					'Living with a complex medical condition shouldn’t mean spending your life in a hospital ward or institutional setting. Our complex care service brings high-acuity, clinically overseen support directly into your home, enabling you to live safely and comfortably with your family.',
				),
				'sections' => array(
					array(
						// Was "Complex Care Competencies": training language, and it read as a claim about staff sign-off (client agreed the change, Nov 2026).
						'heading' => 'Complex Care We Provide',
						'paras' => array(),
						'items' => array(
							array(
								'',
								'Tracheostomy and airway management',
							),
							array(
								'',
								'Enteral feeding (PEG / RIG / NG tubes)',
							),
							array(
								'',
								'Complex bowel, stoma, and catheter care',
							),
							array(
								'',
								'Epilepsy and seizure management, including buccal midazolam',
							),
							array(
								'',
								'Ventilator support and respiratory care',
							),
							array(
								'',
								'Complex moving, handling, and pressure area care',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'How are care workers trained for complex care?',
						'Every care worker undergoes bespoke, client-specific clinical training and competency sign-off by a qualified clinical lead before working independently.',
					),
					array(
						'Can complex care be funded by the NHS?',
						'Many clients with complex, ongoing clinical needs qualify for NHS Continuing Healthcare (CHC) or a Personal Health Budget. We can guide you through the process.',
					),
				),
			),
			'disability-care' => array(
				'title' => 'Empowering Disability Care Built Around Your Rights',
				'intro' => array(
					'Our disability care service is rooted in empowerment, equality, and removing everyday barriers. Whether you live with a physical disability, sensory impairment, or learning disability, we work with you, never for you, to support the life you want to lead.',
					'Guided by CQC person-centred care standards and Positive Behaviour Support (PBS) frameworks where applicable, our teams focus on your abilities, communication preferences, and personal goals.',
				),
				'sections' => array(
					array(
						'heading' => 'How We Support You',
						'paras' => array(),
						'items' => array(
							array(
								'Physical disability support',
								'Safe transfers, hoist and equipment use, personal care, and accessible outings.',
							),
							array(
								'Sensory impairment support',
								'Adapting communication, guiding, and keeping home environments safely organised.',
							),
							array(
								'Learning disability support',
								'Encouraging independence, daily routines, community participation, and emotional wellbeing.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Do you support communication aids or Makaton?',
						'Yes. We tailor our communication to your exact preferences, including assistive technology, visual aids, and non-verbal cues.',
					),
					array(
						'Can my family be involved in creating my care plan?',
						'Yes, as much or as little as you choose. You are always at the centre of every decision.',
					),
				),
			),
			'dementia-care' => array(
				'title' => 'Specialist Dementia Care in Familiar Surroundings',
				'intro' => array(
					'For someone living with dementia or Alzheimer’s, familiar surroundings, cherished belongings, and predictable routines provide vital comfort and reassurance. Moving into an unfamiliar environment can increase distress, which is why staying at home with specialist support makes such a difference.',
				),
				'sections' => array(
					array(
						'heading' => 'Our Dementia-Friendly Approach',
						'paras' => array(),
						'items' => array(
							array(
								'Consistency of faces',
								'A small, dedicated team of dementia-trained care workers to reduce confusion and build trust.',
							),
							array(
								'Calm, reassuring routines',
								'Gentle prompts for washing, dressing, eating, and hydration.',
							),
							array(
								'Memory-focused engagement',
								'Activities, music, and conversation tailored to your loved one’s life story.',
							),
							array(
								'Home safety & wandering support',
								'Practical environmental tips and overnight supervision where needed.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'What if my loved one becomes anxious or refuses care?',
						'Our dementia-trained care workers use calm validation, distraction, and unhurried communication techniques to gently reduce distress.',
					),
					array(
						'Do you support all types of dementia?',
						'Yes, including Alzheimer’s disease, vascular dementia, Lewy body dementia, and frontotemporal dementia.',
					),
				),
			),
			'stroke-care' => array(
				'title' => 'Rebuilding Confidence With Stroke Care at Home',
				'intro' => array(
					'Returning home after a stroke is a major milestone, but it can also feel overwhelming for both the survivor and their family. Our stroke care service provides patient, encouraging support that complements your NHS rehabilitation.',
				),
				'sections' => array(
					array(
						'heading' => 'How We Support Stroke Recovery',
						'paras' => array(),
						'items' => array(
							array(
								'Rehabilitation encouragement',
								'Assisting with daily physiotherapy and occupational therapy exercises set by your NHS team.',
							),
							array(
								'Mobility and personal care',
								'Safe transfers, help with one-sided weakness, and regaining independence with daily tasks.',
							),
							array(
								'Communication patience',
								'Supporting individuals experiencing aphasia or speech difficulties without rushing.',
							),
							array(
								'Secondary prevention',
								'Reliable medication prompts and nutritious meal preparation (including modified diets if advised by a speech and language therapist).',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Can care start the day my loved one leaves hospital?',
						'We will do our best to make that happen. We speak to the hospital discharge coordinators directly, so that support can be ready at home.',
					),
					array(
						'Can we reduce care hours as recovery progresses?',
						'Absolutely. Our goal is to help you regain as much independence as possible and adjust your care plan as you get stronger.',
					),
				),
			),
			'parkinsons-care' => array(
				'title' => 'Flexible, Informed Parkinson’s Care at Home',
				'intro' => array(
					'Living with Parkinson’s means symptoms can fluctuate from day to day, and even hour to hour. Our Parkinson’s care is built around flexibility, empathy, and a deep understanding of the condition.',
				),
				'sections' => array(
					array(
						'heading' => 'What Makes Our Parkinson’s Care Different',
						'paras' => array(),
						'items' => array(
							array(
								'Time-critical medication support',
								'Parkinson’s medication works best taken at the times prescribed, so visits are planned around those times.',
							),
							array(
								'Fall prevention and mobility',
								'Patient assistance with freezing episodes, transfers, and using living aids safely.',
							),
							array(
								'Swallowing and nutrition',
								'Unhurried mealtimes and hydration support tailored to your needs.',
							),
							array(
								'Emotional wellbeing',
								'Companionship and encouragement to stay active and engaged, helping combat anxiety or low mood.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Can visit times be matched strictly to medication schedules?',
						'Yes. We prioritise time-critical medication calls in our scheduling so doses are never delayed.',
					),
					array(
						'Do you work alongside Parkinson’s Nurse Specialists?',
						'Yes. With your consent, we communicate closely with your Parkinson’s nurse, GP, and physiotherapist.',
					),
				),
			),
			'cancer-care' => array(
				'title' => 'Compassionate Cancer Care in the Comfort of Home',
				'intro' => array(
					'Whether you are undergoing chemotherapy or radiotherapy, recovering from surgery, or living with advanced cancer, having practical and emotional support at home takes a huge weight off you and your family.',
					'As Macmillan Cancer Support notes, home care teams work hand-in-hand with GPs, district nurses, occupational therapists, and physiotherapists to help with personal care, mobility, and daily tasks so people can remain at home as much as possible.',
				),
				'sections' => array(
					array(
						'heading' => 'How We Can Help You',
						'paras' => array(),
						'items' => array(
							array(
								'During treatment',
								'Managing fatigue, preparing gentle meals when appetite is low, keeping the house tidy, and attending hospital appointments.',
							),
							array(
								'Personal care and comfort',
								'Sensitive help with washing, dressing, and rest routines.',
							),
							array(
								'Family respite',
								'Stepping in so partners and family members can rest and simply spend quality time together, rather than being full-time carers.',
							),
							array(
								'Palliative and end-of-life care',
								'Seamless transition to round-the-clock comfort care if and when needed.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Can cancer care be flexible around good days and bad days?',
						'Yes. We understand treatment side effects vary, so our care workers adapt each visit to how you are feeling that day.',
					),
					array(
						'Are your care workers trained in infection control?',
						'Yes. Strict infection prevention and hygiene protocols are followed at every visit to protect immunocompromised clients.',
					),
				),
			),
			'nursing-care' => array(
				'title' => 'Clinically Overseen Nursing Care at Home',
				'intro' => array(
					'When a health condition requires clinical skill alongside daily personal care, having registered nursing oversight at home provides essential safety and reassurance.',
				),
				'sections' => array(
					array(
						'heading' => 'Personal Care vs. Nursing Care',
						'paras' => array(),
						'items' => array(
							array(
								'',
								'Personal care is delivered by trained care assistants and covers everyday support such as washing, dressing, mobility, and medication prompts.',
							),
							array(
								'',
								'Nursing care involves clinical tasks, such as wound management, PEG feeding, complex medication administration, or airway care, delivered directly by a Registered Nurse (RN) or by specially trained complex care workers under strict RN supervision and competency sign-off.',
							),
						),
					),
					array(
						'heading' => 'Understanding NHS Funding for Nursing Care',
						'paras' => array(
							'It is helpful to know how NHS funding works in England:',
						),
						'items' => array(
							array(
								'NHS Continuing Healthcare (CHC)',
								'If you have a complex primary health need, the NHS can fund a complete package of nursing and personal care in your own home, free at the point of delivery.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Do you work alongside NHS District Nurses?',
						'Yes. Many of our clients receive daily personal care from Continuity of Care Services while their NHS District Nurse visits for specific clinical procedures, and we coordinate closely with them.',
					),
					array(
						'How do I know if I need nursing care or personal care?',
						'Our free care assessment will evaluate your clinical and daily needs and clearly advise on the safest level of support.',
					),
				),
			),
			'cerebral-palsy-care' => array(
				'title' => 'Person-Centred Cerebral Palsy Care at Home',
				'intro' => array(
					'Cerebral palsy affects every person differently, from subtle movement challenges to complex physical and communication needs. We reject one-size-fits-all care, designing lifelong, empowering support around your individual strengths and aspirations.',
				),
				'sections' => array(
					array(
						'heading' => 'Coordinated, Empowering Support',
						'paras' => array(
							'In line with CQC Regulation 9 (Person-centred care) and NHS multidisciplinary principles, we coordinate closely with your physiotherapists, occupational therapists, and speech and language therapists to support:',
						),
						'items' => array(
							array(
								'Safe mobility and posture',
								'Expert use of hoists, standing frames, wheelchairs, and splints.',
							),
							array(
								'Communication',
								'Respecting your communication methods, including AAC devices and eye-gaze technology.',
							),
							array(
								'Personal care and nutrition',
								'Discreet personal care, assistance with eating, or PEG feeding where required.',
							),
							array(
								'Active living',
								'Supporting work, study, travel, hobbies, and social independence.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Do you support adults transitioning from children’s services?',
						'Yes. We work closely with young adults and their families to make the transition into adult independent living smooth and empowering.',
					),
					array(
						'Will my care team be trained on my specific mobility equipment?',
						'Yes. Every care worker is trained specifically on your equipment and transfer plans before supporting you.',
					),
				),
			),
			'spinal-injury-care' => array(
				'title' => 'Specialist Spinal Injury Care for Active Living at Home',
				'intro' => array(
					'Returning home after a spinal cord injury (SCI) requires both clinical precision and a positive, enabling mindset. Our specialist spinal injury teams give you the clinical safety you need so you can focus on living an active, independent life.',
				),
				'sections' => array(
					array(
						'heading' => 'Clinical Competencies We Support',
						'paras' => array(
							'Our care workers receive bespoke, clinically signed-off training tailored to your exact level of injury:',
						),
						'items' => array(
							array(
								'Bowel and bladder management',
								'Catheter care, intermittent catheterisation, and established bowel routines.',
							),
							array(
								'Autonomic dysreflexia awareness',
								'Recognising early triggers and symptoms and responding calmly and swiftly.',
							),
							array(
								'Skin integrity and positioning',
								'Regular turning schedules and vigilant pressure-area care.',
							),
							array(
								'Respiratory and mobility support',
								'Assisting with ventilators, cough assist devices, transfers, and physiotherapy exercises.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'Do you work with case managers?',
						'Yes. We regularly partner with specialist SCI case managers, NHS spinal units, and multidisciplinary rehabilitation teams.',
					),
					array(
						'Can my care worker drive my adapted vehicle?',
						'Yes, where required, we can match you with care workers who hold a clean UK driving licence and are insured to drive your WAV.',
					),
				),
			),
			'brain-injury-care' => array(
				'title' => 'Tailored Brain Injury Care & Rehabilitation at Home',
				'intro' => array(
					'Adjusting to life after a traumatic or acquired brain injury (ABI) takes time, structure, and specialist understanding. Whether you are transitioning home from a neurorehabilitation unit or managing long-term cognitive and physical effects, we walk alongside you every step of the way.',
				),
				'sections' => array(
					array(
						'heading' => 'How We Support ABI Survivors',
						'paras' => array(),
						'items' => array(
							array(
								'Structured daily routines',
								'Gentle prompting and memory strategies to reduce cognitive overload.',
							),
							array(
								'Fatigue management',
								'Pacing daily activities so you can achieve your goals without burning out.',
							),
							array(
								'Community reintegration',
								'Rebuilding confidence with shopping, public transport, hobbies, and socialising.',
							),
							array(
								'Rehab teamwork',
								'Working hand-in-hand with neuro-physiotherapists, occupational therapists, neuropsychologists, and case managers.',
							),
						),
					),
				),
				'faqs' => array(
					array(
						'How do you handle behavioural or emotional changes after a brain injury?',
						'Our teams are trained in positive, non-confrontational de-escalation and cognitive support strategies tailored to each individual’s triggers.',
					),
					array(
						'Do you support family members too?',
						'Yes. Brain injury affects the whole family, and our respite and structured daily support help relatives step back into being family members rather than full-time carers.',
					),
				),
			),
		);
	}
	return isset( $copy[ $slug ] ) ? $copy[ $slug ] : array();
}

/**
 * Sentence case for a heading from the pack (house style), keeping acronyms
 * and names: "Two Types of Overnight Care" becomes "Two types of overnight care".
 *
 * @param string $text Heading.
 * @return string
 */
function ccs_sentence_case( $text ) {
	$keep  = array( 'CQC', 'NHS', 'GP', 'GPs', 'ABI', 'PEG', 'RIG', 'NG', 'Parkinson’s', 'Alzheimer’s', 'Kent', 'Maidstone', 'Continuity', 'Makaton', 'I' );
	$words = explode( ' ', (string) $text );
	foreach ( $words as $i => $word ) {
		$bare = trim( $word, '.,:;?!()&' );
		if ( in_array( $bare, $keep, true ) || ( strlen( $bare ) > 1 && strtoupper( $bare ) === $bare && preg_match( '/[A-Z]/', $bare ) ) ) {
			continue;
		}
		$words[ $i ] = 0 === $i ? $word : mb_strtolower( $word );
	}
	return implode( ' ', $words );
}
