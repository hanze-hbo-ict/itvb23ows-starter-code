pipeline {
    agent { docker { image 'php:8.3.0-alpine3.18' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
        docker.image('sonarsource/sonar-scanner-cli').inside {
                stage('Sonarqube') {
                    sh 'sonar-scanner -Dsonar.projectKey=ows -Dsonar.host.url=http://sonarqube:9000/'
                }
            }
//         stage('SonarQube Analysis') {
//             steps {
//                 script {
//                     def scannerHome = tool 'SonarQube_Scanner'
//                     def scannerExecutable = "${scannerHome}/bin/sonar-scanner"
//                     withSonarQubeEnv('SonarQube') {
//                         sh "${scannerExecutable} -Dsonar.projectKey=ows -Dsonar.host.url=http://sonarqube:9000/"
//                     }
//                 }
//             }
//         }
    }
}