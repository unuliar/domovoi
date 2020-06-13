app.controller('MeetingsRoomController', function ($scope, $rootScope, $routeParams) {
    $rootScope.checkAuth();
    $rootScope.setLoader(true);

    $rootScope.pageTitle = `Собрание №${$routeParams.meeting_id}`;

    $scope.messages = [];

    $scope.currentMeeting = {
        title: "Решаем важные вопросы",
        date: "22 Мая, 2020 - 18:00",
        text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam pharetra facilisis ullamcorper. Praesent porta leo ac sem bibendum sollicitudin. Fusce finibus, nibh id interdum consequat, ipsum metus varius augue, vel semper eros velit a urna. Nunc feugiat felis ut accumsan vulputate. Cras sit amet nulla id sem eleifend rhoncus. Etiam sollicitudin a sem vel lobortis. Aenean mollis malesuada purus eget porta. Cras sagittis ante a metus semper condimentum. Praesent vel tellus maximus, cursus sem vitae, blandit purus. Ut a mi a libero efficitur tincidunt sed a quam. Nulla cursus nisi in rutrum malesuada. Cras varius dui ac ante egestas sollicitudin. Integer congue est nec ipsum facilisis consequat. Proin a tellus enim. Nullam et erat ut nulla tempus condimentum.\n" +
            "\n" +
            "Praesent iaculis mauris nisl, id faucibus massa bibendum vel. Donec aliquam leo at suscipit ornare. Suspendisse enim metus, iaculis et dui in, tempus semper dolor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas placerat diam sit amet erat convallis vestibulum. Duis ut lorem vehicula felis ultricies accumsan. Mauris in bibendum nisl. In volutpat dignissim ex et posuere. Suspendisse potenti. Nunc eu urna et sem maximus gravida a vel mauris.",
        polls: [
            {
                id: 1,
                question: "Вы хотите новую детскую площадку?"
            },
            {
                id: 2,
                question: "Вы хотите новую лавочку у подьезда?"
            },
            {
                id: 3,
                question: "Вы хотите новую машину?"
            }
        ],
        attachments: []
    };

    $rootScope.apiCall(
        'GET',
        'meeting/get',
        {
            id: $routeParams.meeting_id
        },
        (result) => {
            $scope.$apply(() => {
                $scope.messages = result.data.meeting.chat_messages;
            });

        }
    );
    /** Connection */
    const SOCKET_ADDR = $rootScope.host;
    const SOCKET_PORT = '8081';

    const chatBox = document.getElementById("chatbox");
    const wsFormat = `ws://${SOCKET_ADDR}:${SOCKET_PORT}?meeting_id=${$routeParams.meeting_id}`;

    $scope.socket = new WebSocket(wsFormat);

    $scope.messageBox = '';


    $scope.socket.onmessage = (event) => {
        console.log(event);

        const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;

        let message = JSON.parse(event.data);

        $scope.$apply(() => {
            $scope.messages.push(message);
        });

        if (isScrolledToBottom) {
            chatBox.scrollTop = chatBox.scrollHeight - chatBox.clientHeight
        }
    };

    $scope.socket.onopen = () => {
        $rootScope.setLoader(false);
    };

    $scope.socket.onerror = (error) => {
        console.log("Something went wrong with connecting to WS server, located on: " + error.target.url);

        let new_socket = new WebSocket(wsFormat);

        new_socket.onmessage = $scope.socket.onmessage;
        new_socket.onopen = $scope.socket.onopen;
        new_socket.onerror = $scope.socket.onerror;

        $scope.socket = new_socket;
    };

    $scope.sendMessage = () => {
        if($scope.messageBox != '') {
            $scope.socket.send(JSON.stringify({
                user: $rootScope.currentUser,
                msg: $scope.messageBox,
                meeting_id: $routeParams.meeting_id
            }));

            $scope.messageBox = '';
        }
    };

    $scope.params = $routeParams;
});