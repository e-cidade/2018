<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhdepend
class cl_rhdepend { 
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
   var $rh31_codigo = 0; 
   var $rh31_regist = 0; 
   var $rh31_nome = null; 
   var $rh31_dtnasc_dia = null; 
   var $rh31_dtnasc_mes = null; 
   var $rh31_dtnasc_ano = null; 
   var $rh31_dtnasc = null; 
   var $rh31_gparen = null; 
   var $rh31_depend = null; 
   var $rh31_irf = null; 
   var $rh31_especi = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh31_codigo = int8 = C�digo 
                 rh31_regist = int4 = Matr�cula do Servidor 
                 rh31_nome = varchar(40) = Nome do Dependente 
                 rh31_dtnasc = date = Data de Nascimento 
                 rh31_gparen = varchar(1) = Parentesco 
                 rh31_depend = varchar(1) = Sal�rio Fam�lia 
                 rh31_irf = varchar(1) = IRF 
                 rh31_especi = varchar(1) = Especial 
                 ";
   //funcao construtor da classe 
   function cl_rhdepend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdepend"); 
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
       $this->rh31_codigo = ($this->rh31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_codigo"]:$this->rh31_codigo);
       $this->rh31_regist = ($this->rh31_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_regist"]:$this->rh31_regist);
       $this->rh31_nome = ($this->rh31_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_nome"]:$this->rh31_nome);
       if($this->rh31_dtnasc == ""){
         $this->rh31_dtnasc_dia = ($this->rh31_dtnasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc_dia"]:$this->rh31_dtnasc_dia);
         $this->rh31_dtnasc_mes = ($this->rh31_dtnasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc_mes"]:$this->rh31_dtnasc_mes);
         $this->rh31_dtnasc_ano = ($this->rh31_dtnasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc_ano"]:$this->rh31_dtnasc_ano);
         if($this->rh31_dtnasc_dia != ""){
            $this->rh31_dtnasc = $this->rh31_dtnasc_ano."-".$this->rh31_dtnasc_mes."-".$this->rh31_dtnasc_dia;
         }
       }
       $this->rh31_gparen = ($this->rh31_gparen == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_gparen"]:$this->rh31_gparen);
       $this->rh31_depend = ($this->rh31_depend == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_depend"]:$this->rh31_depend);
       $this->rh31_irf = ($this->rh31_irf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_irf"]:$this->rh31_irf);
       $this->rh31_especi = ($this->rh31_especi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_especi"]:$this->rh31_especi);
     }else{
       $this->rh31_codigo = ($this->rh31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh31_codigo"]:$this->rh31_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($rh31_codigo){ 
      $this->atualizacampos();
     if($this->rh31_regist == null ){ 
       $this->erro_sql = " Campo Matr�cula do Servidor nao Informado.";
       $this->erro_campo = "rh31_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh31_nome == null ){ 
       $this->erro_sql = " Campo Nome do Dependente nao Informado.";
       $this->erro_campo = "rh31_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh31_dtnasc == null ){ 
       $this->erro_sql = " Campo Data de Nascimento nao Informado.";
       $this->erro_campo = "rh31_dtnasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh31_gparen == null ){ 
       $this->erro_sql = " Campo Parentesco nao Informado.";
       $this->erro_campo = "rh31_gparen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh31_depend == null ){ 
       $this->erro_sql = " Campo Sal�rio Fam�lia nao Informado.";
       $this->erro_campo = "rh31_depend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh31_irf == null ){ 
       $this->erro_sql = " Campo IRF nao Informado.";
       $this->erro_campo = "rh31_irf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh31_especi == null ){ 
       $this->erro_sql = " Campo Especial nao Informado.";
       $this->erro_campo = "rh31_especi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh31_codigo == "" || $rh31_codigo == null ){
       $result = db_query("select nextval('rhdepend_rh31_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdepend_rh31_codigo_seq do campo: rh31_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh31_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdepend_rh31_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh31_codigo)){
         $this->erro_sql = " Campo rh31_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh31_codigo = $rh31_codigo; 
       }
     }
     if(($this->rh31_codigo == null) || ($this->rh31_codigo == "") ){ 
       $this->erro_sql = " Campo rh31_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdepend(
                                       rh31_codigo 
                                      ,rh31_regist 
                                      ,rh31_nome 
                                      ,rh31_dtnasc 
                                      ,rh31_gparen 
                                      ,rh31_depend 
                                      ,rh31_irf 
                                      ,rh31_especi 
                       )
                values (
                                $this->rh31_codigo 
                               ,$this->rh31_regist 
                               ,'$this->rh31_nome' 
                               ,".($this->rh31_dtnasc == "null" || $this->rh31_dtnasc == ""?"null":"'".$this->rh31_dtnasc."'")." 
                               ,'$this->rh31_gparen' 
                               ,'$this->rh31_depend' 
                               ,'$this->rh31_irf' 
                               ,'$this->rh31_especi' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dependentes ($this->rh31_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dependentes j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dependentes ($this->rh31_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh31_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh31_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7640,'$this->rh31_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1186,7640,'','".AddSlashes(pg_result($resaco,0,'rh31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7150,'','".AddSlashes(pg_result($resaco,0,'rh31_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7151,'','".AddSlashes(pg_result($resaco,0,'rh31_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7152,'','".AddSlashes(pg_result($resaco,0,'rh31_dtnasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7153,'','".AddSlashes(pg_result($resaco,0,'rh31_gparen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7154,'','".AddSlashes(pg_result($resaco,0,'rh31_depend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7155,'','".AddSlashes(pg_result($resaco,0,'rh31_irf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1186,7156,'','".AddSlashes(pg_result($resaco,0,'rh31_especi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh31_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rhdepend set ";
     $virgula = "";
     if(trim($this->rh31_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_codigo"])){ 
       $sql  .= $virgula." rh31_codigo = $this->rh31_codigo ";
       $virgula = ",";
       if(trim($this->rh31_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "rh31_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh31_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_regist"])){ 
       $sql  .= $virgula." rh31_regist = $this->rh31_regist ";
       $virgula = ",";
       if(trim($this->rh31_regist) == null ){ 
         $this->erro_sql = " Campo Matr�cula do Servidor nao Informado.";
         $this->erro_campo = "rh31_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh31_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_nome"])){ 
       $sql  .= $virgula." rh31_nome = '$this->rh31_nome' ";
       $virgula = ",";
       if(trim($this->rh31_nome) == null ){ 
         $this->erro_sql = " Campo Nome do Dependente nao Informado.";
         $this->erro_campo = "rh31_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh31_dtnasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc_dia"] !="") ){ 
       $sql  .= $virgula." rh31_dtnasc = '$this->rh31_dtnasc' ";
       $virgula = ",";
       if(trim($this->rh31_dtnasc) == null ){ 
         $this->erro_sql = " Campo Data de Nascimento nao Informado.";
         $this->erro_campo = "rh31_dtnasc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc_dia"])){ 
         $sql  .= $virgula." rh31_dtnasc = null ";
         $virgula = ",";
         if(trim($this->rh31_dtnasc) == null ){ 
           $this->erro_sql = " Campo Data de Nascimento nao Informado.";
           $this->erro_campo = "rh31_dtnasc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh31_gparen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_gparen"])){ 
       $sql  .= $virgula." rh31_gparen = '$this->rh31_gparen' ";
       $virgula = ",";
       if(trim($this->rh31_gparen) == null ){ 
         $this->erro_sql = " Campo Parentesco nao Informado.";
         $this->erro_campo = "rh31_gparen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh31_depend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_depend"])){ 
       $sql  .= $virgula." rh31_depend = '$this->rh31_depend' ";
       $virgula = ",";
       if(trim($this->rh31_depend) == null ){ 
         $this->erro_sql = " Campo Sal�rio Fam�lia nao Informado.";
         $this->erro_campo = "rh31_depend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh31_irf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_irf"])){ 
       $sql  .= $virgula." rh31_irf = '$this->rh31_irf' ";
       $virgula = ",";
       if(trim($this->rh31_irf) == null ){ 
         $this->erro_sql = " Campo IRF nao Informado.";
         $this->erro_campo = "rh31_irf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh31_especi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh31_especi"])){ 
       $sql  .= $virgula." rh31_especi = '$this->rh31_especi' ";
       $virgula = ",";
       if(trim($this->rh31_especi) == null ){ 
         $this->erro_sql = " Campo Especial nao Informado.";
         $this->erro_campo = "rh31_especi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh31_codigo!=null){
       $sql .= " rh31_codigo = $this->rh31_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh31_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7640,'$this->rh31_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1186,7640,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_codigo'))."','$this->rh31_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_regist"]))
           $resac = db_query("insert into db_acount values($acount,1186,7150,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_regist'))."','$this->rh31_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_nome"]))
           $resac = db_query("insert into db_acount values($acount,1186,7151,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_nome'))."','$this->rh31_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_dtnasc"]))
           $resac = db_query("insert into db_acount values($acount,1186,7152,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_dtnasc'))."','$this->rh31_dtnasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_gparen"]))
           $resac = db_query("insert into db_acount values($acount,1186,7153,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_gparen'))."','$this->rh31_gparen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_depend"]))
           $resac = db_query("insert into db_acount values($acount,1186,7154,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_depend'))."','$this->rh31_depend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_irf"]))
           $resac = db_query("insert into db_acount values($acount,1186,7155,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_irf'))."','$this->rh31_irf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh31_especi"]))
           $resac = db_query("insert into db_acount values($acount,1186,7156,'".AddSlashes(pg_result($resaco,$conresaco,'rh31_especi'))."','$this->rh31_especi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dependentes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh31_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dependentes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh31_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh31_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh31_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh31_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7640,'$rh31_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1186,7640,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7150,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7151,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7152,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_dtnasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7153,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_gparen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7154,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_depend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7155,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_irf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1186,7156,'','".AddSlashes(pg_result($resaco,$iresaco,'rh31_especi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhdepend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh31_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh31_codigo = $rh31_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dependentes nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh31_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dependentes nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh31_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh31_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhdepend";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdepend ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhdepend.rh31_regist";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
		                                         and  rhpessoalmov.rh02_anousu = ".db_anofolha()."
																						 and  rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
																						 and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
		                                  and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
		                                    and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($rh31_codigo!=null ){
         $sql2 .= " where rhdepend.rh31_codigo = $rh31_codigo "; 
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
   function sql_query_file ( $rh31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdepend ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh31_codigo!=null ){
         $sql2 .= " where rhdepend.rh31_codigo = $rh31_codigo "; 
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
   function sql_query_cgm ( $rh31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdepend ";
     $sql .= "      inner join rhpessoal    on rhpessoal.rh01_regist = rhdepend.rh31_regist ";
     $sql .= "      inner join cgm          on cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhpessoalmov on rhpessoalmov.rh02_anousu = ".db_anofolha()."
                                           and rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
                                           and rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
																					 and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh31_codigo!=null ){
         $sql2 .= " where rhdepend.rh31_codigo = $rh31_codigo "; 
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
   function sql_query_relPREVID ( $rh31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdepend ";
     $sql .= "      inner join rhpessoal     on rhpessoal.rh01_regist     = rhdepend.rh31_regist";
     $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
		                                        and rhpessoalmov.rh02_anousu  = ".db_anofolha()." 
																						and rhpessoalmov.rh02_mesusu  = ".db_mesfolha()."
																						and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm";
     $sql .= "      left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes";
     $sql2 = "";
     if($dbwhere==""){
       if($rh31_codigo!=null ){
         $sql2 .= " where rhdepend.rh31_codigo = $rh31_codigo "; 
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