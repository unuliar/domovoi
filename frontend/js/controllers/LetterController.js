app.controller('LetterController', function ($scope, $rootScope) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Коллективные письма в УК";

    $scope.activeLetter = null;

    $scope.letters = [
        {
            id: 1,
            title: "Убрать УК",
            date: "22 мая, 2020",
            text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam pharetra facilisis ullamcorper. Praesent porta leo ac sem bibendum sollicitudin. Fusce finibus, nibh id interdum consequat, ipsum metus varius augue, vel semper eros velit a urna. Nunc feugiat felis ut accumsan vulputate. Cras sit amet nulla id sem eleifend rhoncus. Etiam sollicitudin a sem vel lobortis. Aenean mollis malesuada purus eget porta. Cras sagittis ante a metus semper condimentum. Praesent vel tellus maximus, cursus sem vitae, blandit purus. Ut a mi a libero efficitur tincidunt sed a quam. Nulla cursus nisi in rutrum malesuada. Cras varius dui ac ante egestas sollicitudin. Integer congue est nec ipsum facilisis consequat. Proin a tellus enim. Nullam et erat ut nulla tempus condimentum.\n" +
                "\n" +
                "Praesent iaculis mauris nisl, id faucibus massa bibendum vel. Donec aliquam leo at suscipit ornare. Suspendisse enim metus, iaculis et dui in, tempus semper dolor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas placerat diam sit amet erat convallis vestibulum. Duis ut lorem vehicula felis ultricies accumsan. Mauris in bibendum nisl. In volutpat dignissim ex et posuere. Suspendisse potenti. Nunc eu urna et sem maximus gravida a vel mauris.",
            user: {
                name: "Николай",
                last_name: "Иванов",
                email: "nikolai.ivanov@mail.ru",
                avatar: '/img/avatars/demo1.png'
            },
            signatories: [],
            attachments: [],
            classList: {}
        },

        {
            id: 2,
            title: "Убрать УК 2",
            date: "23 мая, 2020",
            text: "",
            user: {
                name: "Николай",
                last_name: "Иванов",
                email: "nikolai.ivanov@mail.ru",
                avatar: '/img/avatars/demo1.png'
            },
            signatories: [],
            attachments: [],
            classList: {}
        },
        {
            id: 3,
            title: "Много платим",
            date: "22 мая, 2020",
            text: "",
            user: {
                name: "Николай",
                last_name: "Иванов",
                email: "nikolai.ivanov@mail.ru",
                avatar: '/img/avatars/demo1.png'
            },
            signatories: [],
            attachments: [],
            classList: {}
        },

        {
            id: 4,
            title: "Очень дорого",
            date: "23 мая, 2020",
            text: "",
            user: {
                name: "Николай",
                last_name: "Иванов",
                email: "nikolai.ivanov@mail.ru",
                avatar: '/img/avatars/demo1.png'
            },
            signatories: [],
            attachments: [],
            classList: {}
        }
    ];

    /**
     * Setting current letter to specified
     * @param id
     */
    $scope.setActiveLetter = letter => {
        $scope.letters.forEach((letter) => {
            letter.classList = {};
        });

        letter.classList = {"list_item_active": true};

        $scope.activeLetter = letter;
    };
});