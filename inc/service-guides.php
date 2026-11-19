<?php
/**
 * Plain-English questions and answers for each service page (Nov 2026).
 *
 * The client supplied sixteen pages to read (Age UK, the WHO, Home Instead,
 * Trinity Homecare and others) "so you know what to put on the services
 * pages". What those pages share is a short set of answers a family needs
 * before they ring: what this kind of care is, who it is for, how it differs
 * from the care it is confused with, and how it can be paid for. These are
 * written fresh from that reading, not copied from any of them.
 *
 * Two kinds of sentence, kept apart on purpose:
 * - general facts about a kind of care, or about how care is funded in
 *   England, which are true of any provider;
 * - what Continuity of Care Services does, which is taken only from what the
 *   site already says (its service pages and published rates). Nothing new
 *   is claimed about the company here.
 *
 * Shown in the "Questions families ask" accordion when a service has no
 * questions of its own in the editor (Services > edit > FAQs); the client's
 * own questions replace these as soon as there are any.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The questions for one service.
 *
 * @param string $slug Service slug.
 * @return array<int, array{q:string,a:string}> Question and plain-text answer.
 */
function ccs_service_guide_faqs( $slug ) {
	$funding = __( 'Some people pay for their own care. Others have part or all of it paid by Kent County Council after a free needs assessment, or by the NHS through Continuing Healthcare when the main need is a health need. Attendance Allowance and Personal Independence Payment are not means-tested and can be put towards care. Our Costs page lists every rate, and we can talk the options through with you.', 'ccs-wp-theme' );

	$guides = array(
		'domiciliary-care'   => array(),
		'respite-care'       => array(
			array(
				__( 'Is it selfish to take a break?', 'ccs-wp-theme' ),
				__( 'No. Caring for someone is tiring, and carers who never rest often become unwell themselves. A regular break is one of the things that lets people carry on caring for longer.', 'ccs-wp-theme' ),
			),
			array(
				__( 'Can I get help arranging or paying for it?', 'ccs-wp-theme' ),
				__( 'As a carer you can ask Kent County Council for a carer’s assessment, which is free and looks at what would help you, including breaks. If you receive Carer’s Allowance, check how a break affects it before you book.', 'ccs-wp-theme' ),
			),
		),
		'complex-care'       => array(),
		'companionship-care' => array(
			array(
				__( 'What if more help is needed later?', 'ccs-wp-theme' ),
				__( 'Needs change. Because we also provide domiciliary, overnight and complex care, visits can grow into more practical help without starting again with a new provider.', 'ccs-wp-theme' ),
			),
		),
		'overnight-care'     => array(
			array(
				__( 'What do we need at home?', 'ccs-wp-theme' ),
				__( 'For a sleep-in night the carer needs somewhere to sleep, such as a spare bed. For a waking night they need only a comfortable place to sit near the person they are caring for.', 'ccs-wp-theme' ),
			),
		),
		'continuous-care'    => array(),
		'palliative-care'    => array(
			array(
				__( 'Can it be given at home?', 'ccs-wp-theme' ),
				__( 'Yes. Many people want to be at home, with their own things and the people they love. We work with your hospice team, GP and family so the care at home follows the same plan.', 'ccs-wp-theme' ),
			),
		),
		'disability-care'    => array(
			array(
				__( 'Is there help that is specific to disability?', 'ccs-wp-theme' ),
				__( 'Many people use a personal budget or direct payments from Kent County Council after an assessment. Disability benefits such as Personal Independence Payment can also be put towards support.', 'ccs-wp-theme' ),
			),
		),
	);

	$out = array();
	foreach ( isset( $guides[ $slug ] ) ? $guides[ $slug ] : array() as $pair ) {
		$out[] = array(
			'q' => $pair[0],
			'a' => $pair[1],
		);
	}

	// On every page: how care starts (the homepage's own sentence) and how it can be paid for.
	$out[] = array(
		'q' => __( 'How quickly can care start?', 'ccs-wp-theme' ),
		'a' => function_exists( 'ccs_copy' ) ? ccs_copy( 'steps_lede' ) : '',
	);
	$out[] = array(
		'q' => __( 'How can care be paid for?', 'ccs-wp-theme' ),
		'a' => $funding,
	);

	return $out;
}

/**
 * The information blocks for one service page: what this care is, who it
 * helps, and how its options differ. General facts about the kind of care;
 * anything about the company repeats what the site already says.
 *
 * @param string $slug Service slug.
 * @return array{what:array,who:array,compare:array}|array{} Empty when unknown.
 */
function ccs_service_guide( $slug ) {
	$guides = array(
		'domiciliary-care'   => array(
			'what'    => array(
				__( 'What is domiciliary care?', 'ccs-wp-theme' ),
				__( 'Domiciliary care is another name for home care. A carer visits you in your own home to help with everyday things: washing and dressing, meals, medication, a bit of housework, or simply company. It lets people stay in the home they know.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Someone finding washing, dressing or cooking harder than it used to be', 'ccs-wp-theme' ),
				__( 'Someone coming home after a fall or a stay in hospital', 'ccs-wp-theme' ),
				__( 'Someone living alone whose family worry between visits', 'ccs-wp-theme' ),
				__( 'A couple where one partner is doing all the caring', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Care at home or a care home?', 'ccs-wp-theme' ),
				array(
					array( __( 'Care at home', 'ccs-wp-theme' ), __( 'The care comes to you, for the visits you need, and the rest of the day stays your own. Many people start with a few visits a week and add more only if things change.', 'ccs-wp-theme' ) ),
					array( __( 'A care home', 'ccs-wp-theme' ), __( 'You move to where the care is. It can be the right choice when someone needs help through the whole day and night and home is no longer safe for them.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'respite-care'       => array(
			'what'    => array(
				__( 'What is respite care?', 'ccs-wp-theme' ),
				__( 'Respite care is temporary care that gives a family carer a break. Someone else steps in, so the person you look after is cared for while you rest, work, go away or look after your own health.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Family carers who need a rest or a full night’s sleep', 'ccs-wp-theme' ),
				__( 'Carers going on holiday, or into hospital themselves', 'ccs-wp-theme' ),
				__( 'Carers who work and need cover on set days', 'ccs-wp-theme' ),
				__( 'Families who want the same break every week', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Ways to take a break', 'ccs-wp-theme' ),
				array(
					array( __( 'A few hours', 'ccs-wp-theme' ), __( 'Time for an appointment, the shops, a friend or a sleep, while a carer keeps the usual routine going at home.', 'ccs-wp-theme' ) ),
					array( __( 'Overnight or longer', 'ccs-wp-theme' ), __( 'Cover for a night, a weekend or a holiday. Care stays at home, so meals, medication and surroundings do not change.', 'ccs-wp-theme' ) ),
					array( __( 'A regular slot', 'ccs-wp-theme' ), __( 'The same break every week or fortnight, so there is always a rest to look forward to.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'complex-care'       => array(
			'what'    => array(
				__( 'What is complex care?', 'ccs-wp-theme' ),
				__( 'Complex care is care at home for someone with significant, ongoing health needs. It brings clinical tasks, such as PEG feeding, tracheostomy care or ventilation support, together with everyday personal care, in one plan and from one trained team.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Adults and children with a long-term condition that needs clinical care', 'ccs-wp-theme' ),
				__( 'People leaving hospital with new equipment, such as a feeding tube', 'ccs-wp-theme' ),
				__( 'Families who have been managing clinical tasks themselves', 'ccs-wp-theme' ),
				__( 'People whose care is funded by NHS Continuing Healthcare', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Complex care or everyday home care?', 'ccs-wp-theme' ),
				array(
					array( __( 'Everyday home care', 'ccs-wp-theme' ), __( 'Help with washing, dressing, meals, medication and company. It needs kindness, time and good training, but not clinical procedures.', 'ccs-wp-theme' ) ),
					array( __( 'Complex care', 'ccs-wp-theme' ), __( 'Adds clinical tasks set out in the care plan. Carers are trained for those specific tasks, and the care is planned with the nurses, therapists and GP involved.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'companionship-care' => array(
			'what'    => array(
				__( 'What is companionship care?', 'ccs-wp-theme' ),
				__( 'Companionship care is regular time with a carer whose job is company: conversation, a shared activity, a walk, or a trip to the shops or a café. It is for the hours that feel long, not for medical or personal care.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People who live alone and miss having someone to talk to', 'ccs-wp-theme' ),
				__( 'People who have stopped getting out as much as they used to', 'ccs-wp-theme' ),
				__( 'People whose family live too far away to visit often', 'ccs-wp-theme' ),
				__( 'Relatives who want to know someone is calling in', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Company or care?', 'ccs-wp-theme' ),
				array(
					array( __( 'Companionship care', 'ccs-wp-theme' ), __( 'Time, conversation and outings. There is no washing, dressing or medication involved, only good company on a regular day.', 'ccs-wp-theme' ) ),
					array( __( 'Domiciliary care', 'ccs-wp-theme' ), __( 'Adds practical help with washing, dressing, meals and medication. Many people begin with company and add this later.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'overnight-care'     => array(
			'what'    => array(
				__( 'What is overnight care?', 'ccs-wp-theme' ),
				__( 'Overnight care means a carer is in the home through the night. They help with things like getting to the toilet, changing position, medication and reassurance, so the person is safe and their family can sleep.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People who are unsettled or at risk of falling at night', 'ccs-wp-theme' ),
				__( 'People who need help with continence or changing position', 'ccs-wp-theme' ),
				__( 'People recovering after a stay in hospital', 'ccs-wp-theme' ),
				__( 'Family carers who need unbroken sleep', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Sleep-in night or waking night?', 'ccs-wp-theme' ),
				array(
					array( __( 'Sleep-in night', 'ccs-wp-theme' ), __( 'The carer sleeps in your home and gets up if they are needed. It suits someone who usually wakes once or twice.', 'ccs-wp-theme' ) ),
					array( __( 'Waking night', 'ccs-wp-theme' ), __( 'The carer stays awake all night. It suits someone who needs help or checking often.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'continuous-care'    => array(
			'what'    => array(
				__( 'What is continuous care?', 'ccs-wp-theme' ),
				__( 'Continuous care is care around the clock: there is always a carer in the home, day and night. It is provided in planned shifts by a small team who know the person’s routines, with a handover between each shift.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People who cannot safely be left alone', 'ccs-wp-theme' ),
				__( 'People living with advanced dementia', 'ccs-wp-theme' ),
				__( 'People recovering from a stroke or a serious illness', 'ccs-wp-theme' ),
				__( 'People who need help through the night as well as the day', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Care in shifts or live-in care?', 'ccs-wp-theme' ),
				array(
					array( __( 'Care in shifts', 'ccs-wp-theme' ), __( 'Carers take turns, so whoever is on duty is awake and working, at any hour. This is how we provide continuous care.', 'ccs-wp-theme' ) ),
					array( __( 'Live-in care', 'ccs-wp-theme' ), __( 'One carer lives in the home. They have to rest and sleep, so they are not on duty all night.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'palliative-care'    => array(
			'what'    => array(
				__( 'What is palliative care?', 'ccs-wp-theme' ),
				__( 'Palliative care is care for someone with an illness that cannot be cured. Its aim is comfort and quality of life: easing pain and other symptoms, and supporting the person and their family emotionally and practically.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People living with a life-limiting illness, at any stage', 'ccs-wp-theme' ),
				__( 'People who want to be cared for at home', 'ccs-wp-theme' ),
				__( 'Families who need support, rest and someone to turn to', 'ccs-wp-theme' ),
				__( 'People already under a hospice or community nursing team', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Palliative care or end-of-life care?', 'ccs-wp-theme' ),
				array(
					array( __( 'Palliative care', 'ccs-wp-theme' ), __( 'Can begin at any stage of a serious illness and can run alongside treatment. It is about living as well as possible.', 'ccs-wp-theme' ) ),
					array( __( 'End-of-life care', 'ccs-wp-theme' ), __( 'The part of palliative care given in the final weeks and days, focused on comfort, dignity and being with the people who matter.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'disability-care'    => array(
			'what'    => array(
				__( 'What is disability care?', 'ccs-wp-theme' ),
				__( 'Disability care is support shaped around a person’s disability and their own goals. It can include personal care, help with moving around, communication, cooking and money, and getting out to activities, work or community groups.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Children and adults with a physical disability', 'ccs-wp-theme' ),
				__( 'People with a learning disability', 'ccs-wp-theme' ),
				__( 'People with an autism spectrum condition', 'ccs-wp-theme' ),
				__( 'Parents and families who need support alongside them', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'At home and out and about', 'ccs-wp-theme' ),
				array(
					array( __( 'Support at home', 'ccs-wp-theme' ), __( 'Personal care, help with meals and medication, and building everyday skills such as cooking and managing money.', 'ccs-wp-theme' ) ),
					array( __( 'Support out and about', 'ccs-wp-theme' ), __( 'Getting to activities, day trips, groups and work, with someone alongside who knows what helps.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'personal-care' => array(
			'what'    => array(
				__( 'What is personal care?', 'ccs-wp-theme' ),
				__( 'Personal care is help with the private, everyday things: washing, dressing, using the toilet and getting ready for the day or for bed. It is given at home, by the same small group of carers, in the way you like things done.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People who find washing or dressing difficult or tiring', 'ccs-wp-theme' ),
				__( 'People who are unsteady in the bath or shower', 'ccs-wp-theme' ),
				__( 'People coming home from hospital who need help for a while', 'ccs-wp-theme' ),
				__( 'Family carers who find this part of caring hard to do', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Personal care or domiciliary care?', 'ccs-wp-theme' ),
				array(
					array( __( 'Personal care', 'ccs-wp-theme' ), __( 'Help with washing, dressing, the toilet and getting in and out of bed. It can be the only help someone has.', 'ccs-wp-theme' ) ),
					array( __( 'Domiciliary care', 'ccs-wp-theme' ), __( 'Personal care together with meals, medication, housework and company, in the same visits.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'elderly-care' => array(
			'what'    => array(
				__( 'What is elderly care at home?', 'ccs-wp-theme' ),
				__( 'Elderly care at home is support for an older person in their own home, so they can keep their routine, their surroundings and their independence. It can be a little help each week or visits every day.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Older people who want to stay in their own home', 'ccs-wp-theme' ),
				__( 'People who are managing, but not quite as well as last year', 'ccs-wp-theme' ),
				__( 'People living alone whose family worry between visits', 'ccs-wp-theme' ),
				__( 'Couples where one partner is doing all the caring', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'A little help or a lot?', 'ccs-wp-theme' ),
				array(
					array( __( 'A few visits a week', 'ccs-wp-theme' ), __( 'Help with a bath, the shopping, a hot meal or simply some company. Many people start here.', 'ccs-wp-theme' ) ),
					array( __( 'Care every day', 'ccs-wp-theme' ), __( 'Morning, lunchtime and evening visits for washing, dressing, meals and medication, with more added if things change.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'long-term-care' => array(
			'what'    => array(
				__( 'What is long-term care?', 'ccs-wp-theme' ),
				__( 'Long-term care is ongoing support at home for a condition that is not going to go away. It is planned for the years, not the weeks, and is reviewed and adjusted as needs change.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People living with a long-term or progressive condition', 'ccs-wp-theme' ),
				__( 'People whose needs are likely to grow over time', 'ccs-wp-theme' ),
				__( 'People who want the same carers for the long run', 'ccs-wp-theme' ),
				__( 'Families planning ahead for the years to come', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Short-term or long-term?', 'ccs-wp-theme' ),
				array(
					array( __( 'Short-term care', 'ccs-wp-theme' ), __( 'Help for a few weeks, for example after an illness or while a family carer is away.', 'ccs-wp-theme' ) ),
					array( __( 'Long-term care', 'ccs-wp-theme' ), __( 'The same small team for as long as it is needed, with the care plan reviewed as things change.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'younger-adults-care' => array(
			'what'    => array(
				__( 'What is support for younger adults?', 'ccs-wp-theme' ),
				__( 'Support for younger adults is care for people of working age who live with a disability, an injury or a long-term condition. It is built around the life the person wants to lead, at home, at work and out with friends.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Adults living with a physical or learning disability', 'ccs-wp-theme' ),
				__( 'People recovering from a serious injury or illness', 'ccs-wp-theme' ),
				__( 'People with a long-term condition who want to stay independent', 'ccs-wp-theme' ),
				__( 'Parents of a young adult moving into adult services', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'At home and out and about', 'ccs-wp-theme' ),
				array(
					array( __( 'Support at home', 'ccs-wp-theme' ), __( 'Personal care, meals, medication and help to run a home, at times that suit your day.', 'ccs-wp-theme' ) ),
					array( __( 'Support out and about', 'ccs-wp-theme' ), __( 'Getting to work, study, appointments and social life, with someone alongside who knows what helps.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'dementia-care' => array(
			'what'    => array(
				__( 'What is dementia care at home?', 'ccs-wp-theme' ),
				__( 'Dementia care at home is support for someone living with dementia in the place they know best. Familiar rooms, routines and faces help people feel settled, and a small team of regular carers keeps those things steady.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People recently diagnosed who want to stay at home', 'ccs-wp-theme' ),
				__( 'People who are becoming confused, forgetful or unsafe alone', 'ccs-wp-theme' ),
				__( 'People with advanced dementia who need help through the day', 'ccs-wp-theme' ),
				__( 'Husbands, wives and families who need support and a break', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Care at home or a care home?', 'ccs-wp-theme' ),
				array(
					array( __( 'Care at home', 'ccs-wp-theme' ), __( 'The person stays among their own things and keeps their own routine, with carers who come to know them.', 'ccs-wp-theme' ) ),
					array( __( 'A care home', 'ccs-wp-theme' ), __( 'A move to a new place with staff on hand all day and night. It can be right when home is no longer safe.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'stroke-care' => array(
			'what'    => array(
				__( 'What is stroke care at home?', 'ccs-wp-theme' ),
				__( 'Stroke care at home is support with daily life after a stroke. A stroke can affect movement, speech, swallowing, memory and mood, and recovery can take months or years. Care at home works alongside the therapists who guide that recovery.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People coming home after a stroke', 'ccs-wp-theme' ),
				__( 'People who need help with washing, dressing and moving', 'ccs-wp-theme' ),
				__( 'People with speech or swallowing difficulties after a stroke', 'ccs-wp-theme' ),
				__( 'Families adjusting to a sudden change', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Recovery and everyday life', 'ccs-wp-theme' ),
				array(
					array( __( 'Support with recovery', 'ccs-wp-theme' ), __( 'Help to keep up the exercises and routines set by physiotherapists, occupational therapists and speech therapists.', 'ccs-wp-theme' ) ),
					array( __( 'Support with everyday life', 'ccs-wp-theme' ), __( 'Washing, dressing, meals, medication and getting about, for as long as it is needed.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'parkinsons-care' => array(
			'what'    => array(
				__( 'What is Parkinson’s care at home?', 'ccs-wp-theme' ),
				__( 'Parkinson’s care at home is support for someone living with Parkinson’s, a condition that affects movement and can change from hour to hour. Care is flexible, and careful about medication times, which matter a great deal.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People with Parkinson’s who need help at set times of day', 'ccs-wp-theme' ),
				__( 'People whose symptoms vary from day to day', 'ccs-wp-theme' ),
				__( 'People who are at risk of falls', 'ccs-wp-theme' ),
				__( 'Partners who have become full-time carers', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Earlier and later stages', 'ccs-wp-theme' ),
				array(
					array( __( 'In the earlier stages', 'ccs-wp-theme' ), __( 'A little help at the times of day that are hardest, and support to stay active and independent.', 'ccs-wp-theme' ) ),
					array( __( 'As needs grow', 'ccs-wp-theme' ), __( 'More frequent visits, help with moving, eating and personal care, and overnight support if it is needed.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'cancer-care' => array(
			'what'    => array(
				__( 'What is cancer care at home?', 'ccs-wp-theme' ),
				__( 'Cancer care at home is practical and personal support for someone living with cancer, during treatment, in recovery or when treatment has stopped. It is not medical treatment; it is the help that makes the days manageable.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People having chemotherapy or radiotherapy', 'ccs-wp-theme' ),
				__( 'People recovering at home after surgery', 'ccs-wp-theme' ),
				__( 'People living with cancer in the long term', 'ccs-wp-theme' ),
				__( 'Families who need support and rest', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'During treatment and beyond', 'ccs-wp-theme' ),
				array(
					array( __( 'During treatment', 'ccs-wp-theme' ), __( 'Help on the days when energy is low: meals, washing, housework and getting to appointments.', 'ccs-wp-theme' ) ),
					array( __( 'When treatment ends', 'ccs-wp-theme' ), __( 'Support to regain strength, or comfort and palliative care at home when that is what is needed.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'nursing-care' => array(
			'what'    => array(
				__( 'What is nursing care at home?', 'ccs-wp-theme' ),
				__( 'Nursing care at home is clinical care planned and overseen by nurses, for people whose health needs call for more than everyday support. It lets people with serious or complex conditions be cared for at home.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People with clinical needs, such as wounds, catheters or stomas', 'ccs-wp-theme' ),
				__( 'People leaving hospital who still need clinical care', 'ccs-wp-theme' ),
				__( 'People with complex or long-term conditions', 'ccs-wp-theme' ),
				__( 'Families who have been carrying out clinical tasks themselves', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Nursing care or home care?', 'ccs-wp-theme' ),
				array(
					array( __( 'Home care', 'ccs-wp-theme' ), __( 'Help with washing, dressing, meals, medication and company, from trained carers.', 'ccs-wp-theme' ) ),
					array( __( 'Nursing care', 'ccs-wp-theme' ), __( 'Adds clinical tasks set out in the care plan, planned and overseen by nurses, alongside your GP and community teams.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'cerebral-palsy-care' => array(
			'what'    => array(
				__( 'What is cerebral palsy care?', 'ccs-wp-theme' ),
				__( 'Cerebral palsy care is support for a child or adult whose movement, posture or coordination is affected by cerebral palsy. Needs vary widely, so care is planned around the individual and what they want to do.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'Children with cerebral palsy and their parents', 'ccs-wp-theme' ),
				__( 'Adults with cerebral palsy living at home', 'ccs-wp-theme' ),
				__( 'People who need help with moving, eating or personal care', 'ccs-wp-theme' ),
				__( 'Young people moving from children’s to adult services', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'For children and for adults', 'ccs-wp-theme' ),
				array(
					array( __( 'For children', 'ccs-wp-theme' ), __( 'Support that fits around school, therapy and family life, working with parents and the professionals involved.', 'ccs-wp-theme' ) ),
					array( __( 'For adults', 'ccs-wp-theme' ), __( 'Support to live as independently as possible: at home, at work and in the community.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'spinal-injury-care' => array(
			'what'    => array(
				__( 'What is spinal injury care?', 'ccs-wp-theme' ),
				__( 'Spinal injury care is support at home for someone living with a spinal cord injury. It combines personal care with clinical tasks such as bowel, bladder and skin care, from carers trained for them.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People coming home from a spinal injuries unit', 'ccs-wp-theme' ),
				__( 'People with paraplegia or tetraplegia', 'ccs-wp-theme' ),
				__( 'People who need help with bowel, bladder and skin care', 'ccs-wp-theme' ),
				__( 'Families who have been providing care themselves', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Coming home and the long term', 'ccs-wp-theme' ),
				array(
					array( __( 'Coming home', 'ccs-wp-theme' ), __( 'Care in place for the move from hospital, with carers trained for the routines the spinal unit has set.', 'ccs-wp-theme' ) ),
					array( __( 'The long term', 'ccs-wp-theme' ), __( 'A steady team for the years ahead, supporting the life you choose at home, at work and out and about.', 'ccs-wp-theme' ) ),
				),
			),
		),
		'brain-injury-care' => array(
			'what'    => array(
				__( 'What is brain injury care?', 'ccs-wp-theme' ),
				__( 'Brain injury care is support at home for someone whose brain has been injured, by an accident, a stroke, an infection or lack of oxygen. It can affect memory, mood, movement and speech, and care is patient and consistent.', 'ccs-wp-theme' ),
			),
			'who'     => array(
				__( 'People coming home after a brain injury', 'ccs-wp-theme' ),
				__( 'People who need structure and prompts through the day', 'ccs-wp-theme' ),
				__( 'People with changes in memory, mood or behaviour', 'ccs-wp-theme' ),
				__( 'Families learning to live with a new situation', 'ccs-wp-theme' ),
			),
			'compare' => array(
				__( 'Rehabilitation and daily life', 'ccs-wp-theme' ),
				array(
					array( __( 'Support with rehabilitation', 'ccs-wp-theme' ), __( 'Help to follow the plan set by the rehabilitation team, with the same carers so progress is noticed.', 'ccs-wp-theme' ) ),
					array( __( 'Support with daily life', 'ccs-wp-theme' ), __( 'Routines, personal care, meals and getting out, for as long as it is needed.', 'ccs-wp-theme' ) ),
				),
			),
		),
	);

	return isset( $guides[ $slug ] ) ? $guides[ $slug ] : array();
}
