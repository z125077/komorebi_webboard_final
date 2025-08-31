<?php
// Run this file once to create the database and tables
require_once 'config.php';

try {
    // Create users table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        author_id VARCHAR(20) PRIMARY KEY,
        author_name VARCHAR(100) NOT NULL,
        author_email VARCHAR(150) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        join_date DATE NOT NULL,
        last_login DATE NOT NULL
    )
");

    // Create posts table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            post_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            author_id VARCHAR(20) NOT NULL,
            category VARCHAR(50) NOT NULL,
            created_at DATETIME NOT NULL,
            views INT DEFAULT 0,
            likes INT DEFAULT 0,
            FOREIGN KEY (author_id) REFERENCES users(author_id) ON DELETE CASCADE
        )
    ");

    // Create comments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS comments (
            comment_id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            author_name VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE
        )
    ");

    // Create likes table to track individual likes
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS post_likes (
            like_id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_ip VARCHAR(45) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_like (post_id, user_ip),
            FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE
        )
    ");

    echo "Database tables created successfully!\n";

    // Insert sample users data
    $users_data = [
        ['author_1', 'Kenji Tanaka', 'kenji.tanaka@example.com', '2024-01-15', '2025-06-26'],
        ['author_2', 'Mai Sato', 'mai.sato@example.com', '2024-02-20', '2025-06-26'],
        ['author_3', 'Ren Yamamoto', 'ren.yamamoto@example.com', '2024-03-10', '2025-06-25'],
        ['author_4', 'Yui Suzuki', 'yui.suzuki@example.com', '2024-04-05', '2025-06-25'],
        ['author_5', 'Hiroshi Kobayashi', 'hiroshi.k@example.com', '2024-05-12', '2025-06-26'],
        ['author_6', 'Akari Takahashi', 'akari.t@example.com', '2024-06-30', '2025-06-25'],
        ['author_7', 'Sota Ito', 'sota.ito@example.com', '2024-07-22', '2025-06-26'],
        ['author_8', 'Yuna Nakamura', 'yuna.nakamura@example.com', '2024-08-18', '2025-06-25'],
        ['author_9', 'Haruto Watanabe', 'haruto.w@example.com', '2024-09-09', '2025-06-26'],
        ['author_10', 'Aoi Ito', 'aoi.ito@example.com', '2024-10-28', '2025-06-25'],
        ['author_11', 'Ryota Kato', 'ryota.kato@example.com', '2024-11-14', '2025-06-26'],
        ['author_12', 'Sakura Yoshida', 'sakura.y@example.com', '2024-12-01', '2025-06-25'],
        ['author_13', 'Kaito Yamada', 'kaito.yamada@example.com', '2025-01-08', '2025-06-25'],
        ['author_14', 'Hinata Tanaka', 'hinata.tanaka@example.com', '2025-02-25', '2025-06-26'],
        ['author_15', 'Yuki Mori', 'yuki.mori@example.com', '2025-03-17', '2025-06-25'],
        ['author_16', 'Riku Hayashi', 'riku.hayashi@example.com', '2025-04-03', '2025-06-26'],
        ['author_17', 'Mio Shimizu', 'mio.shimizu@example.com', '2025-05-29', '2025-06-25'],
        ['author_18', 'Takumi Inoue', 'takumi.i@example.com', '2025-06-05', '2025-06-26'],
        ['author_19', 'Yuma Kimura', 'yuma.kimura@example.com', '2025-06-11', '2025-06-25'],
        ['author_20', 'Hina Takahashi', 'hina.takahashi@example.com', '2025-06-19', '2025-06-26']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO users (author_id, author_name, author_email, join_date, last_login) VALUES (?, ?, ?, ?, ?)");
    foreach ($users_data as $user) {
        $stmt->execute($user);
    }

    // Insert sample posts data (first few posts as example)
$posts_data = [
    [1, 'Train delays are my new normal', "The trains here are incredibly punctual, but when there's a delay, it's a huge headache. The platform gets impossibly crowded, and the staff are overwhelmed with questions. I've been late to work twice this week because of minor delays, which is a big deal here. It's a stressful start to my day.", 'author_1', 'everyday life', '2025-06-25 10:30:00', 750, 92],
    [2, 'Finding an apartment is a nightmare', "The rental process in Japan is so different and complicated. There are so many fees and requirements, like key money and guarantor companies, and the contracts are all in Japanese. I've been searching for weeks and feel like I'm getting nowhere. It's a very stressful experience for a foreigner.", 'author_2', 'everyday life', '2025-06-24 18:00:00', 1120, 155],
    [3, 'The humidity is unbearable', "I heard about Japan's humid summers, but nothing prepared me for this. My clothes feel damp all the time, and I'm sweating from the moment I step outside. I can barely sleep at night without the air conditioning on. It's a completely different level of heat and humidity than I'm used to.", 'author_3', 'everyday life', '2025-06-25 11:15:00', 890, 121],
    [4, 'Tiny apartments, big problems', "My new apartment is so small that I have no room to store anything. My futon takes up the entire living space, and I have to put it away every morning. It's a struggle to find a place for all my belongings, and I feel like I'm living out of a suitcase. I miss having a proper closet and storage space.", 'author_4', 'everyday life', '2025-06-23 20:45:00', 640, 78],
    [5, 'Shopping for clothes is difficult', "As a foreigner, it's hard to find my size in clothes here. The sizes are much smaller, and I can't find shoes that fit me. I've had to resort to ordering everything online, which is not ideal. I wish there were more options for larger sizes in stores.", 'author_5', 'everyday life', '2025-06-22 14:20:00', 910, 103],
    [6, 'Garbage sorting is a puzzle', "I feel like I need a PhD to sort my garbage. The rules are so strict and complicated, with different days for burnable, non-burnable, plastic, and so on. I'm always worried I'm going to make a mistake and get my garbage rejected. It's a daily source of anxiety.", 'author_6', 'garbage sorting', '2025-06-21 16:30:00', 1500, 210],
    [7, 'Confusing plastic recycling', "I'm still confused about what counts as plastic recycling. Is it just bottles? What about the plastic wrap on my food? The labels on the bags are so hard to understand, and I'm scared of putting the wrong thing in the bag. I wish there was a clear, simple guide in English.", 'author_7', 'garbage sorting', '2025-06-20 09:10:00', 1230, 170],
    [8, 'The garbage collection times are so early', "I have to wake up at 7 AM to put out my garbage before the truck comes. If I miss it, I have to wait a whole week to throw it out. I'm not a morning person, and this is a real struggle for me. I wish the collection times were a bit later in the day.", 'author_8', 'garbage sorting', '2025-06-19 12:00:00', 980, 128],
    [9, 'I\'m worried about my garbage inspection', 'My neighbor told me the garbage collectors check bags to make sure everything is sorted correctly. I\'m constantly paranoid that I\'ll get a sticker on my bag telling me it\'s wrong. It feels like I\'m being graded on my trash. The pressure is real.', 'author_9', 'garbage sorting', '2025-06-18 17:50:00', 1800, 250],
    [10, 'The lack of public trash cans', "It's so hard to find a public trash can anywhere. I end up carrying my garbage with me all day until I get home. It's a good system for keeping the streets clean, but it's very inconvenient for people who are out and about. It's a cultural shock.", 'author_10', 'garbage sorting', '2025-06-17 13:40:00', 1650, 230],
    [11, 'Bowing is still a challenge', "I know bowing is a sign of respect, but I'm still not sure when to do it and how deep to bow. I feel awkward and self-conscious every time. I'm afraid of offending someone by doing it wrong. It's a cultural difference that takes a lot of getting used to.", 'author_11', 'cultural different', '2025-06-26 09:00:00', 520, 65],
    [12, 'Reading the room is hard', "In Japan, a lot of communication is non-verbal and based on 'reading the room.' As a direct person, I find it hard to understand what people really mean. It's a constant guessing game, and I'm worried about missing social cues. It's a big hurdle in forming relationships.", 'author_12', 'cultural different', '2025-06-25 15:20:00', 880, 110],
    [13, 'The silence on the train', "I'm used to people talking on their phones on the train, but here, it's completely silent. It's a nice change of pace, but it also feels a bit isolating. I feel like I have to be silent and respectful, and I'm afraid of making any noise. It's an interesting cultural norm.", 'author_13', 'cultural different', '2025-06-24 10:00:00', 990, 145],
    [14, 'The concept of \'honne\' and \'tatemae\'', "I'm still struggling with the concept of 'honne' (true feelings) and 'tatemae' (public facade). It's hard to know what people are really thinking and feeling, which makes it difficult to build trust. It's a complex social dynamic that I'm still trying to navigate.", 'author_14', 'cultural different', '2025-06-23 18:30:00', 760, 95],
    [15, 'New Year\'s is so quiet', "I was expecting a big, loud party for New Year's Eve, but it was so quiet and family-oriented. It was a nice experience, but it was different from what I'm used to. I missed the fireworks and the countdown parties. It's a much more reflective holiday here.", 'author_15', 'cultural different', '2025-06-22 11:00:00', 450, 55],
    [16, 'Navigating the hospital is a challenge', "I had to go to the hospital for a minor injury, and it was a huge challenge. The forms were all in Japanese, and the staff didn't speak much English. I had to use a translation app to communicate, which was stressful in a medical setting. It's a daunting process to go through.", 'author_16', 'hospital related', '2025-06-21 08:45:00', 1400, 205],
    [17, 'Finding an English-speaking doctor', "It's so hard to find a doctor who speaks English. I have to travel far to a specific clinic, and the wait times are incredibly long. I wish there were more options for English speakers, especially for emergencies. It's a big source of stress for expats.", 'author_17', 'hospital related', '2025-06-20 14:10:00', 1650, 220],
    [18, 'The long wait times', "Even with an appointment, the wait times at the hospital are incredibly long. I've waited for hours just to see the doctor for a few minutes. It's a frustrating system, and I feel like my time isn't valued. I wish it was more efficient.", 'author_18', 'hospital related', '2025-06-19 16:50:00', 1100, 165],
    [19, 'The cost of healthcare', "I was surprised by how much I had to pay for my check-up, even with insurance. The system is different from what I'm used to, and I'm not sure what my insurance covers. I'm afraid of getting a huge bill for a minor issue. It's a financially stressful situation.", 'author_19', 'hospital related', '2025-06-18 21:00:00', 950, 130],
    [20, 'The paperwork is a nightmare', "The amount of paperwork I had to fill out at the hospital was overwhelming. There were so many forms, and they were all in Japanese. I had to ask for help from a friend, which was embarrassing. I wish there were more resources for foreigners.", 'author_20', 'hospital related', '2025-06-17 07:30:00', 780, 105],
    [21, 'The constant noise from announcements', "There are so many announcements on the train and in public spaces. The constant noise is overwhelming, and I can't even understand what they're saying. I feel like my brain is on overload every time I go out. I miss the quiet.", 'author_1', 'others', '2025-06-26 08:30:00', 450, 55],
    [22, 'Lack of personal space', "Public spaces are so crowded, and there's a serious lack of personal space. I'm constantly being bumped into, and I have to squeeze past people everywhere. It's a bit overwhelming for an introvert. I miss having some breathing room.", 'author_2', 'others', '2025-06-25 17:00:00', 670, 88],
    [23, 'The price of fruit is shocking', "I was shocked by how expensive fruit is here, especially melons and strawberries. I can't afford to buy them regularly, which is a shame. It's a small thing, but it's a big cultural difference. I miss the cheap fruit from my home country.", 'author_3', 'others', '2025-06-24 22:00:00', 1100, 150],
    [24, 'No shoes inside!', "I'm still getting used to taking off my shoes every time I enter a house or a restaurant. I forget sometimes and get a weird look from people. It's a small cultural rule, but it's hard to remember. It's a good habit, but it's a hard one to learn.", 'author_4', 'others', '2025-06-23 11:30:00', 920, 135],
    [25, 'The vending machines are everywhere', "There are vending machines for everything, and they're on every street corner. It's convenient, but it's also a bit strange. It feels like a futuristic world where you can get anything from a machine. I'm both impressed and overwhelmed.", 'author_5', 'others', '2025-06-22 13:00:00', 810, 112],
    [26, 'The paperwork for everything', "I feel like I'm constantly filling out paperwork for everything, from opening a bank account to getting a library card. There's so much bureaucracy, and it's all in Japanese. It's a very time-consuming and frustrating process.", 'author_6', 'others', '2025-06-21 19:40:00', 1300, 190],
    [27, 'The heat is relentless', "The summers here are so hot and humid, and the heat is relentless. I'm constantly sweating, and I can't escape it. I'm starting to miss the cold winters of my home country. I don't know how people do it.", 'author_7', 'others', '2025-06-20 11:50:00', 750, 98],
    [28, 'The small portions', "The portion sizes at restaurants are so small. I'm used to bigger meals, and I often have to order a second dish. It's healthier, but it's not very filling for a big eater like me. I miss the super-sized meals from back home.", 'author_8', 'others', '2025-06-19 09:30:00', 620, 81],
    [29, 'The language barrier is real', "Even after studying Japanese for a while, the language barrier is still very real. I struggle with complex conversations, and I often misunderstand people. It's isolating and makes it hard to connect with people on a deeper level. I'm always trying to improve.", 'author_9', 'others', '2025-06-18 10:00:00', 1850, 250],
    [30, 'The lack of public heating', "My apartment doesn't have central heating, and the winters are so cold. I have to rely on a small heater, which is not very effective. It's a huge difference from what I'm used to. I'm always cold in my apartment.", 'author_10', 'others', '2025-06-17 23:15:00', 1020, 140],
    [31, 'Learning to cook Japanese food is hard', "I'm trying to learn how to cook Japanese food, but it's hard to find the right ingredients and spices. I'm also not used to the cooking methods, and my food never tastes as good as the restaurant food. It's a slow learning process.", 'author_11', 'everyday life', '2025-06-26 13:00:00', 350, 45],
    [32, 'Lack of sidewalks', "Some of the streets here don't have sidewalks, so I have to walk in the road with cars. It's very dangerous and stressful. I'm always worried about getting hit by a car. I wish there were more pedestrian-friendly streets.", 'author_12', 'everyday life', '2025-06-25 14:00:00', 580, 72],
    [33, 'The summer bugs', 'The summer bugs here are huge and everywhere. I\'m terrified of the giant spiders and cockroaches that I see on the street. I have to check my shoes before I put them on. It\'s a huge phobia of mine.', 'author_13', 'everyday life', '2025-06-24 16:30:00', 700, 98],
    [34, 'Buying medicine is confusing', "I had a cold and tried to buy medicine, but the labels were all in Japanese. I couldn't figure out which medicine was for what. I had to ask a pharmacist for help, which was embarrassing. I wish there were more English labels.", 'author_14', 'hospital related', '2025-06-23 19:00:00', 1100, 160],
    [35, 'Dental care is expensive', "I had a toothache and went to the dentist, and the cost was shocking. Even with insurance, it was very expensive. I'm afraid of going back for more work. I wish dental care was more affordable.", 'author_15', 'hospital related', '2025-06-22 20:00:00', 1350, 195],
    [36, 'Lack of late-night options', "Everything closes so early here, especially on weekdays. I miss being able to go out for a late-night dinner or a movie. I have to plan everything in advance. It's a big adjustment for a night owl like me.", 'author_16', 'everyday life', '2025-06-21 22:00:00', 420, 50],
    [37, 'The tatami mats are uncomfortable', "I don't like sleeping on a tatami mat. It's too hard for my back, and I can't get a good night's sleep. I wish I had a proper bed. It's a cultural experience, but it's not a comfortable one.", 'author_17', 'cultural different', '2025-06-20 23:00:00', 680, 85],
    [38, 'The heat is unbearable in summer', "I can't believe how hot it gets in the summer. It's a different kind of heat and humidity that I'm not used to. I feel like I'm constantly melting. I don't know how I'm going to survive the whole summer.", 'author_18', 'everyday life', '2025-06-19 21:00:00', 910, 120],
    [39, 'The formality is exhausting', "The level of formality in every interaction is exhausting. I have to use honorifics and a specific language with everyone, even a store clerk. I miss the casual and relaxed atmosphere of my home country.", 'author_19', 'cultural different', '2025-06-18 18:00:00', 1250, 180],
    [40, 'The tiny toilet seats', "The toilet seats are so tiny and uncomfortable. I feel like I'm going to fall in. I wish they were a bit bigger. It's a minor thing, but it's a funny cultural difference.", 'author_20', 'others', '2025-06-17 17:00:00', 520, 70],
    [41, 'The lack of recycling bins', "I'm used to having recycling bins in public places, but here, it's rare to find one. I have to take my plastic bottles and cans home with me. It's a good system, but it's inconvenient.", 'author_1', 'garbage sorting', '2025-06-26 12:00:00', 850, 110],
    [42, 'The \'shikata ga nai\' attitude', "I'm still struggling with the 'shikata ga nai' (it can't be helped) attitude. I'm a problem-solver, and it's hard for me to just accept things as they are. I wish people were more proactive about fixing problems.", 'author_2', 'cultural different', '2025-06-25 19:00:00', 980, 140],
    [43, 'The small washing machines', "The washing machines are so small, I have to do laundry every day. I miss having a big washer and dryer. It's a small apartment problem that affects my daily life.", 'author_3', 'everyday life', '2025-06-24 20:00:00', 700, 95],
    [44, 'The lack of public showers', "It's hard to find a public shower or bath, and the ones that exist are very expensive. I miss being able to go to a public pool or gym with showers. It's a different way of life.", 'author_4', 'others', '2025-06-23 21:00:00', 450, 60],
    [45, 'The strict school rules', "The school rules for my children are so strict, from the uniforms to the hairstyles. I wish there was more freedom for them to express themselves. It's a different approach to education.", 'author_5', 'cultural different', '2025-06-22 17:00:00', 880, 125],
    [46, 'The lack of vegetarian options', "It's hard to find vegetarian food at restaurants. Most dishes have some kind of meat or fish in them. I often have to explain my diet, which is a bit tiring. I wish there were more vegetarian-friendly options.", 'author_6', 'everyday life', '2025-06-21 14:00:00', 620, 80],
    [47, 'The noise of the cicadas', "The cicadas in the summer are so loud! The constant buzzing is overwhelming, and I can't even have a conversation outside. It's a sign of summer, but it's a very loud one.", 'author_7', 'others', '2025-06-20 16:00:00', 550, 75],
    [48, 'The lack of public seating', "There are no benches or places to sit in public, so I have to stand all the time. It's very tiring, especially after a long day of walking. I wish there were more places to rest.", 'author_8', 'others', '2025-06-19 18:00:00', 700, 90],
    [49, 'The earthquake drills', "The earthquake drills are a bit scary, but they are necessary. I'm still getting used to the alarms and the procedures. It's a constant reminder of the risk of natural disasters.", 'author_9', 'others', '2025-06-18 15:00:00', 920, 130],
    [50, 'The price of rent is so high', "The rent is so expensive, and it's hard to find an affordable place to live. I have to work so hard just to pay for my apartment. I wish the cost of living was lower.", 'author_10', 'everyday life', '2025-06-17 14:00:00', 1400, 200]
];