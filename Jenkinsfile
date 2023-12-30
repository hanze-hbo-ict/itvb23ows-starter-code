/* Requires the Docker Pipeline plugin */
pipeline {
    agent any 
    stages {
        stage('build') {
            steps {
                echo 'Hello World'
            }
        }
        stage('SonarQubeScanner'){
            steps{
                script {def scannerHome = tool 'SonarQubeScanner';}
                withSonarQubeENv('SonarQube'){
                    sh "${scannerHome}/bin/sonar-scanner"
                }
            }
        }
    }
}