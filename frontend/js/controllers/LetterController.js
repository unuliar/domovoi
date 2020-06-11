app.controller('LetterController', function ($scope, $rootScope) {
    $rootScope.pageTitle = "Письма";


    $scope.lettersCollection = [
        {
            categoryName: 'Управляющая компания',
            internal: 'uk',
            collapsed: false,
            letters: [
                {
                    title: "Убрать УК",
                    date: "22 мая, 2020",
                    user: {
                        name: "Николай",
                        last_name: "Иванов",
                        email: "nikolai.ivanov@mail.ru",
                        avatar: '/img/avatars/demo1.png'
                    }
                },

                {
                    title: "Убрать УК 2",
                    date: "23 мая, 2020",
                    user: {
                        name: "Николай",
                        last_name: "Иванов",
                        email: "nikolai.ivanov@mail.ru",
                        avatar: '/img/avatars/demo1.png'
                    }
                }
            ]
        },

        {
            categoryName: 'Платежи',
            internal: 'payments',
            collapsed: false,
            letters: [
                {
                    title: "Много платим",
                    date: "22 мая, 2020",
                    user: {
                        name: "Николай",
                        last_name: "Иванов",
                        email: "nikolai.ivanov@mail.ru",
                        avatar: '/img/avatars/demo1.png'
                    }
                },

                {
                    title: "Очень дорого",
                    date: "23 мая, 2020",
                    user: {
                        name: "Николай",
                        last_name: "Иванов",
                        email: "nikolai.ivanov@mail.ru",
                        avatar: '/img/avatars/demo1.png'
                    }
                }
            ]
        }

    ];
});