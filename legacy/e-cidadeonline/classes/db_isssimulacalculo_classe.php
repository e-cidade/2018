<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: issqn
//CLASSE DA ENTIDADE isssimulacalculo
class cl_isssimulacalculo { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $q130_sequencial = 0; 
   var $q130_cnpjcpf = null; 
   var $q130_razaosocial = null; 
   var $q130_email = null; 
   var $q130_logradouro = 0; 
   var $q130_bairro = 0; 
   var $q130_numero = 0; 
   var $q130_complemento = null; 
   var $q130_zona = 0; 
   var $q130_empregados = 0; 
   var $q130_area = 0; 
   var $q130_datainicio_dia = null; 
   var $q130_datainicio_mes = null; 
   var $q130_datainicio_ano = null; 
   var $q130_datainicio = null; 
   var $q130_telefone = null; 
   var $q130_cadescrito = 0; 
   var $q130_multiplicador = 0; 
   var $q130_datacalculo_dia = null; 
   var $q130_datacalculo_mes = null; 
   var $q130_datacalculo_ano = null; 
   var $q130_datacalculo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q130_sequencial = int4 = Sequencial 
                 q130_cnpjcpf = varchar(14) = CNPJ/CPF 
                 q130_razaosocial = varchar(150) = Razão social 
                 q130_email = varchar(150) = E-mail 
                 q130_logradouro = int4 = Logradouro 
                 q130_bairro = int4 = Bairro 
                 q130_numero = int4 = Número 
                 q130_complemento = varchar(150) = Complemento 
                 q130_zona = int4 = Zona 
                 q130_empregados = int4 = Empregados 
                 q130_area = numeric(10) = Área 
                 q130_datainicio = date = Data de inicio 
                 q130_telefone = varchar(12) = Telefone 
                 q130_cadescrito = int4 = Escritório Contábil 
                 q130_multiplicador = int4 = Multiplicador 
                 q130_datacalculo = date = Data Calculo 
                 ";
   //funcao construtor da classe 
   function cl_isssimulacalculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isssimulacalculo"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->q130_sequencial = ($this->q130_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_sequencial"]:$this->q130_sequencial);
       $this->q130_cnpjcpf = ($this->q130_cnpjcpf == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_cnpjcpf"]:$this->q130_cnpjcpf);
       $this->q130_razaosocial = ($this->q130_razaosocial == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_razaosocial"]:$this->q130_razaosocial);
       $this->q130_email = ($this->q130_email == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_email"]:$this->q130_email);
       $this->q130_logradouro = ($this->q130_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_logradouro"]:$this->q130_logradouro);
       $this->q130_bairro = ($this->q130_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_bairro"]:$this->q130_bairro);
       $this->q130_numero = ($this->q130_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_numero"]:$this->q130_numero);
       $this->q130_complemento = ($this->q130_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_complemento"]:$this->q130_complemento);
       $this->q130_zona = ($this->q130_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_zona"]:$this->q130_zona);
       $this->q130_empregados = ($this->q130_empregados == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_empregados"]:$this->q130_empregados);
       $this->q130_area = ($this->q130_area == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_area"]:$this->q130_area);
       if($this->q130_datainicio == ""){
         $this->q130_datainicio_dia = ($this->q130_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_datainicio_dia"]:$this->q130_datainicio_dia);
         $this->q130_datainicio_mes = ($this->q130_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_datainicio_mes"]:$this->q130_datainicio_mes);
         $this->q130_datainicio_ano = ($this->q130_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_datainicio_ano"]:$this->q130_datainicio_ano);
         if($this->q130_datainicio_dia != ""){
            $this->q130_datainicio = $this->q130_datainicio_ano."-".$this->q130_datainicio_mes."-".$this->q130_datainicio_dia;
         }
       }
       $this->q130_telefone = ($this->q130_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_telefone"]:$this->q130_telefone);
       $this->q130_cadescrito = ($this->q130_cadescrito == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_cadescrito"]:$this->q130_cadescrito);
       $this->q130_multiplicador = ($this->q130_multiplicador == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_multiplicador"]:$this->q130_multiplicador);
       if($this->q130_datacalculo == ""){
         $this->q130_datacalculo_dia = ($this->q130_datacalculo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_datacalculo_dia"]:$this->q130_datacalculo_dia);
         $this->q130_datacalculo_mes = ($this->q130_datacalculo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_datacalculo_mes"]:$this->q130_datacalculo_mes);
         $this->q130_datacalculo_ano = ($this->q130_datacalculo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_datacalculo_ano"]:$this->q130_datacalculo_ano);
         if($this->q130_datacalculo_dia != ""){
            $this->q130_datacalculo = $this->q130_datacalculo_ano."-".$this->q130_datacalculo_mes."-".$this->q130_datacalculo_dia;
         }
       }
     }else{
       $this->q130_sequencial = ($this->q130_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q130_sequencial"]:$this->q130_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q130_sequencial){ 
      $this->atualizacampos();
     if($this->q130_cnpjcpf == null ){ 
       $this->erro_sql = " Campo CNPJ/CPF nao Informado.";
       $this->erro_campo = "q130_cnpjcpf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_razaosocial == null ){ 
       $this->erro_sql = " Campo Razão social nao Informado.";
       $this->erro_campo = "q130_razaosocial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_logradouro == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "q130_logradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "q130_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_numero == null ){ 
       $this->q130_numero = "0";
     }
     if($this->q130_zona == null ){ 
       $this->erro_sql = " Campo Zona nao Informado.";
       $this->erro_campo = "q130_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_empregados == null ){ 
       $this->erro_sql = " Campo Empregados nao Informado.";
       $this->erro_campo = "q130_empregados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_area == null ){ 
       $this->erro_sql = " Campo Área nao Informado.";
       $this->erro_campo = "q130_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_datainicio == null ){ 
       $this->erro_sql = " Campo Data de inicio nao Informado.";
       $this->erro_campo = "q130_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_cadescrito == null ){ 
       $this->q130_cadescrito = "0";
     }
     if($this->q130_multiplicador == null ){ 
       $this->erro_sql = " Campo Multiplicador nao Informado.";
       $this->erro_campo = "q130_multiplicador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q130_datacalculo == null ){ 
       $this->q130_datacalculo = "null";
     }
     if($q130_sequencial == "" || $q130_sequencial == null ){
       $result = db_query("select nextval('isssimulacalculo_q130_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isssimulacalculo_q130_sequencial_seq do campo: q130_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q130_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isssimulacalculo_q130_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q130_sequencial)){
         $this->erro_sql = " Campo q130_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q130_sequencial = $q130_sequencial; 
       }
     }
     if(($this->q130_sequencial == null) || ($this->q130_sequencial == "") ){ 
       $this->erro_sql = " Campo q130_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isssimulacalculo(
                                       q130_sequencial 
                                      ,q130_cnpjcpf 
                                      ,q130_razaosocial 
                                      ,q130_email 
                                      ,q130_logradouro 
                                      ,q130_bairro 
                                      ,q130_numero 
                                      ,q130_complemento 
                                      ,q130_zona 
                                      ,q130_empregados 
                                      ,q130_area 
                                      ,q130_datainicio 
                                      ,q130_telefone 
                                      ,q130_cadescrito 
                                      ,q130_multiplicador 
                                      ,q130_datacalculo 
                       )
                values (
                                $this->q130_sequencial 
                               ,'$this->q130_cnpjcpf' 
                               ,'$this->q130_razaosocial' 
                               ,'$this->q130_email' 
                               ,$this->q130_logradouro 
                               ,$this->q130_bairro 
                               ,$this->q130_numero 
                               ,'$this->q130_complemento' 
                               ,$this->q130_zona 
                               ,$this->q130_empregados 
                               ,$this->q130_area 
                               ,".($this->q130_datainicio == "null" || $this->q130_datainicio == ""?"null":"'".$this->q130_datainicio."'")." 
                               ,'$this->q130_telefone' 
                               ,$this->q130_cadescrito 
                               ,$this->q130_multiplicador 
                               ,".($this->q130_datacalculo == "null" || $this->q130_datacalculo == ""?"null":"'".$this->q130_datacalculo."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Simulação calculo ISSQN ($this->q130_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Simulação calculo ISSQN já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Simulação calculo ISSQN ($this->q130_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q130_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ($q130_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isssimulacalculo set ";
     $virgula = "";
     if(trim($this->q130_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_sequencial"])){ 
       $sql  .= $virgula." q130_sequencial = $this->q130_sequencial ";
       $virgula = ",";
       if(trim($this->q130_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q130_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_cnpjcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_cnpjcpf"])){ 
       $sql  .= $virgula." q130_cnpjcpf = '$this->q130_cnpjcpf' ";
       $virgula = ",";
       if(trim($this->q130_cnpjcpf) == null ){ 
         $this->erro_sql = " Campo CNPJ/CPF nao Informado.";
         $this->erro_campo = "q130_cnpjcpf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_razaosocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_razaosocial"])){ 
       $sql  .= $virgula." q130_razaosocial = '$this->q130_razaosocial' ";
       $virgula = ",";
       if(trim($this->q130_razaosocial) == null ){ 
         $this->erro_sql = " Campo Razão social nao Informado.";
         $this->erro_campo = "q130_razaosocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_email"])){ 
       $sql  .= $virgula." q130_email = '$this->q130_email' ";
       $virgula = ",";
     }
     if(trim($this->q130_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_logradouro"])){ 
       $sql  .= $virgula." q130_logradouro = $this->q130_logradouro ";
       $virgula = ",";
       if(trim($this->q130_logradouro) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "q130_logradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_bairro"])){ 
       $sql  .= $virgula." q130_bairro = $this->q130_bairro ";
       $virgula = ",";
       if(trim($this->q130_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "q130_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_numero"])){ 
        if(trim($this->q130_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q130_numero"])){ 
           $this->q130_numero = "0" ; 
        } 
       $sql  .= $virgula." q130_numero = $this->q130_numero ";
       $virgula = ",";
     }
     if(trim($this->q130_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_complemento"])){ 
       $sql  .= $virgula." q130_complemento = '$this->q130_complemento' ";
       $virgula = ",";
     }
     if(trim($this->q130_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_zona"])){ 
       $sql  .= $virgula." q130_zona = $this->q130_zona ";
       $virgula = ",";
       if(trim($this->q130_zona) == null ){ 
         $this->erro_sql = " Campo Zona nao Informado.";
         $this->erro_campo = "q130_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_empregados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_empregados"])){ 
       $sql  .= $virgula." q130_empregados = $this->q130_empregados ";
       $virgula = ",";
       if(trim($this->q130_empregados) == null ){ 
         $this->erro_sql = " Campo Empregados nao Informado.";
         $this->erro_campo = "q130_empregados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_area"])){ 
       $sql  .= $virgula." q130_area = $this->q130_area ";
       $virgula = ",";
       if(trim($this->q130_area) == null ){ 
         $this->erro_sql = " Campo Área nao Informado.";
         $this->erro_campo = "q130_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q130_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." q130_datainicio = '$this->q130_datainicio' ";
       $virgula = ",";
       if(trim($this->q130_datainicio) == null ){ 
         $this->erro_sql = " Campo Data de inicio nao Informado.";
         $this->erro_campo = "q130_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q130_datainicio_dia"])){ 
         $sql  .= $virgula." q130_datainicio = null ";
         $virgula = ",";
         if(trim($this->q130_datainicio) == null ){ 
           $this->erro_sql = " Campo Data de inicio nao Informado.";
           $this->erro_campo = "q130_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q130_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_telefone"])){ 
       $sql  .= $virgula." q130_telefone = '$this->q130_telefone' ";
       $virgula = ",";
     }
     if(trim($this->q130_cadescrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_cadescrito"])){ 
        if(trim($this->q130_cadescrito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q130_cadescrito"])){ 
           $this->q130_cadescrito = "0" ; 
        } 
       $sql  .= $virgula." q130_cadescrito = $this->q130_cadescrito ";
       $virgula = ",";
     }
     if(trim($this->q130_multiplicador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_multiplicador"])){ 
       $sql  .= $virgula." q130_multiplicador = $this->q130_multiplicador ";
       $virgula = ",";
       if(trim($this->q130_multiplicador) == null ){ 
         $this->erro_sql = " Campo Multiplicador nao Informado.";
         $this->erro_campo = "q130_multiplicador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q130_datacalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q130_datacalculo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q130_datacalculo_dia"] !="") ){ 
       $sql  .= $virgula." q130_datacalculo = '$this->q130_datacalculo' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q130_datacalculo_dia"])){ 
         $sql  .= $virgula." q130_datacalculo = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($q130_sequencial!=null){
       $sql .= " q130_sequencial = $this->q130_sequencial";
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Simulação calculo ISSQN nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q130_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Simulação calculo ISSQN nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q130_sequencial=null,$dbwhere=null) { 
     $sql = " delete from isssimulacalculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q130_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q130_sequencial = $q130_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Simulação calculo ISSQN nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q130_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Simulação calculo ISSQN nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:isssimulacalculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q130_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from isssimulacalculo ";
     $sql .= "      inner join bairro      on  bairro.j13_codi       = isssimulacalculo.q130_bairro";
     $sql .= "      inner join ruas        on  ruas.j14_codigo       = isssimulacalculo.q130_logradouro";
     $sql .= "      inner join zonas       on  zonas.j50_zona        = isssimulacalculo.q130_zona";     
     $sql .= "      left  join cadescrito  on  cadescrito.q86_numcgm = isssimulacalculo.q130_cadescrito";
     $sql .= "      left  join cgm         on  cgm.z01_numcgm        = cadescrito.q86_numcgm";
     $sql .= "      left  join ruascep     on  ruascep.j29_codigo    = ruas.j14_codigo";     
     $sql2 = "";
     if($dbwhere==""){
       if($q130_sequencial!=null ){
         $sql2 .= " where isssimulacalculo.q130_sequencial = $q130_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $q130_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from isssimulacalculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q130_sequencial!=null ){
         $sql2 .= " where isssimulacalculo.q130_sequencial = $q130_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>