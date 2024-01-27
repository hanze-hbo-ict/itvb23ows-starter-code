pipeline {
    agent any
        stage('SonarQube') {
            steps {
                script {
                    scannerHome = tool 'SonarQubeScanner'
                    withSonarQubeEnv() {
                        sh "${scannerHome}/bin/sonar-scanner"
                    }
                }
            }
        }
    }
}