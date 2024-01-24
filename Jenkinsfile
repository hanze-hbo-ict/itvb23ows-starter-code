pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('SonarQube Analysis') {
            steps {
                script { scannerHome = tool 'ows-sonarqube-scanner' }
                withSonarQubeEnv('ows-sonarqube-server') {
                    sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=dev-martijn-ows -Dsonar.login=squ_4f04a369ef4249b3a6c13d1f3d1a68416e6f51e8"
                }
                echo 'Sonarqube working...'
            }
        }
    }

    post {
        success {
            echo 'Pipeline was successful.'
        }
        failure {
            echo 'Failure in pipeline!'
        }
    }
}
