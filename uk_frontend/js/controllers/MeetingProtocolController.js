app.controller('MeetingProtocolController', function ($scope, $rootScope, $routeParams, $filter) {
   /* document.querySelectorAll('link[rel="stylesheet"]')
        .forEach(el => el.parentNode.removeChild(el));*/
   console.log(document.getElementsByTagName("ng-view"));
    //document.body.innerHTML = document.getElementsByTagName("ng-view")[0] ?  document.getElementsByTagName("ng-view")[0].innerHTML : document.body.innerHTML;
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Протокол собрания";


    $scope.init = () => {
        $rootScope.apiCall(
            'GET',
            'meeting/get',
            {
                id: $routeParams.meeting_id
            },
            (result) => {
                console.log(result);
                $scope.meeting = result.data.meeting;
                $scope.meeting.house.city = $scope.meeting.house.address.split(",")[2];

                $scope.head = [

                {"name" : "Адрес многоквартирного дома", "value"  : $scope.meeting.house.address,},
                {"name" : "Вид собрания" , "value" : "Заочное",},
                {"name" : "Время проведения собрания", "value"  : $filter('number')($scope.meeting.planned_date, 1),},
                {"name" : "Место проведения собрания", "value"  : "Информационная система Домовой",},
                {"name" : "Место сбора бланков", "value"  : "Информационная система Домовой",},
                {"name" : "Место подсчета голосов", "value"  : "Информационная система Домовой",},
                {"name" : "Инициатором внеочередного собрания собственников выступил", "value"  : "Информационная система Домовой",},
                {"name" : "Место хранения настоящего протокола", "value"  : "Информационная система Домовой",},
                {"name" : "Количество жилых помещений в МКД", "value" : $scope.meeting.house.residental_premise_count,},
                   {"name" : "Площадь жилых помещений", "value" : $scope.meeting.house.residential_premise_total_square + " ㎡"},
                ];

                let max = 0;
                let max2 = 0;
                for (let q of $scope.meeting.meeting_questions) {
                    if(q.poll) {
                        let agreed = 0;
                        let disagreed = 0;
                        let ignored = 0;
                        for(let result of q.poll._poll_results) {
                            let numberVotes = result.account.ownings[0].size;
                            if(result.result == -1) {
                                disagreed += numberVotes;
                            }
                            if(result.result == 1) {
                                agreed += numberVotes;
                            }
                            if(result.result == 0) {
                                ignored += numberVotes;
                            }
                        }              let all = (agreed+disagreed+ ignored);
                        q.poll.stat = {
                            agr:{sum:agreed, percent : 100* parseFloat(agreed.toString()) / all},
                            dis:{sum:disagreed,percent : 100* parseFloat(disagreed.toString()) / all},
                            ign:{sum:ignored, percent : 100* parseFloat(ignored.toString()) / all },
                        };

                        q.poll.quorum = 100* parseFloat(all.toString()) / $scope.meeting.house.residential_premise_total_square;
                        if (q.poll.quorum > max) {
                            max = q.poll.quorum;
                        }
                        if (all > max2) {
                            max2 = all;
                        }
                    }
                }
                $scope.meeting.quorum = max;
                $scope.meeting.participated = max2;
            });

    };

});