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
//CLASSE DA ENTIDADE rhdirfgeracaodadospessoalvalor
class cl_rhdirfgeracaodadospessoalvalor { 
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
   var $rh98_sequencial = 0; 
   var $rh98_rhdirfgeracaodadospessoal = 0; 
   var $rh98_rhdirftipovalor = 0; 
   var $rh98_tipoirrf = null; 
   var $rh98_mes = 0; 
   var $rh98_valor = 0; 
   var $rh98_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh98_sequencial = int4 = Sequencial 
                 rh98_rhdirfgeracaodadospessoal = int4 = Dirf Dados Pessoais 
                 rh98_rhdirftipovalor = int4 = Tipo de Valor 
                 rh98_tipoirrf = varchar(100) = Tipo Irrf 
                 rh98_mes = int4 = M�s 
                 rh98_valor = int4 = Valor 
                 rh98_instit = int4 = Institui��o 
                 ";
   //funcao construtor da classe 
   function cl_rhdirfgeracaodadospessoalvalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracaodadospessoalvalor"); 
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
       $this->rh98_sequencial = ($this->rh98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_sequencial"]:$this->rh98_sequencial);
       $this->rh98_rhdirfgeracaodadospessoal = ($this->rh98_rhdirfgeracaodadospessoal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_rhdirfgeracaodadospessoal"]:$this->rh98_rhdirfgeracaodadospessoal);
       $this->rh98_rhdirftipovalor = ($this->rh98_rhdirftipovalor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_rhdirftipovalor"]:$this->rh98_rhdirftipovalor);
       $this->rh98_tipoirrf = ($this->rh98_tipoirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_tipoirrf"]:$this->rh98_tipoirrf);
       $this->rh98_mes = ($this->rh98_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_mes"]:$this->rh98_mes);
       $this->rh98_valor = ($this->rh98_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_valor"]:$this->rh98_valor);
       $this->rh98_instit = ($this->rh98_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_instit"]:$this->rh98_instit);
     }else{
       $this->rh98_sequencial = ($this->rh98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh98_sequencial"]:$this->rh98_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh98_sequencial){ 
      $this->atualizacampos();
     if($this->rh98_rhdirfgeracaodadospessoal == null ){ 
       $this->erro_sql = " Campo Dirf Dados Pessoais nao Informado.";
       $this->erro_campo = "rh98_rhdirfgeracaodadospessoal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh98_rhdirftipovalor == null ){ 
       $this->erro_sql = " Campo Tipo de Valor nao Informado.";
       $this->erro_campo = "rh98_rhdirftipovalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh98_tipoirrf == null ){ 
       $this->erro_sql = " Campo Tipo Irrf nao Informado.";
       $this->erro_campo = "rh98_tipoirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh98_mes == null ){ 
       $this->erro_sql = " Campo M�s nao Informado.";
       $this->erro_campo = "rh98_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh98_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh98_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh98_instit == null ){ 
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "rh98_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh98_sequencial == "" || $rh98_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracaodadospessoalvalor_rh98_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracaodadospessoalvalor_rh98_sequencial_seq do campo: rh98_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh98_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdirfgeracaodadospessoalvalor_rh98_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh98_sequencial)){
         $this->erro_sql = " Campo rh98_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh98_sequencial = $rh98_sequencial; 
       }
     }
     if(($this->rh98_sequencial == null) || ($this->rh98_sequencial == "") ){ 
       $this->erro_sql = " Campo rh98_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracaodadospessoalvalor(
                                       rh98_sequencial 
                                      ,rh98_rhdirfgeracaodadospessoal 
                                      ,rh98_rhdirftipovalor 
                                      ,rh98_tipoirrf 
                                      ,rh98_mes 
                                      ,rh98_valor 
                                      ,rh98_instit 
                       )
                values (
                                $this->rh98_sequencial 
                               ,$this->rh98_rhdirfgeracaodadospessoal 
                               ,$this->rh98_rhdirftipovalor 
                               ,'$this->rh98_tipoirrf' 
                               ,$this->rh98_mes 
                               ,$this->rh98_valor 
                               ,$this->rh98_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhdirfgeracaodadospessoalvalor ($this->rh98_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhdirfgeracaodadospessoalvalor j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhdirfgeracaodadospessoalvalor ($this->rh98_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh98_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["ignoreAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->rh98_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17769,'$this->rh98_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3139,17769,'','".AddSlashes(pg_result($resaco,0,'rh98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3139,17771,'','".AddSlashes(pg_result($resaco,0,'rh98_rhdirfgeracaodadospessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3139,17781,'','".AddSlashes(pg_result($resaco,0,'rh98_rhdirftipovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3139,17777,'','".AddSlashes(pg_result($resaco,0,'rh98_tipoirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3139,17778,'','".AddSlashes(pg_result($resaco,0,'rh98_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3139,17779,'','".AddSlashes(pg_result($resaco,0,'rh98_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3139,17805,'','".AddSlashes(pg_result($resaco,0,'rh98_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh98_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdirfgeracaodadospessoalvalor set ";
     $virgula = "";
     if(trim($this->rh98_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_sequencial"])){ 
       $sql  .= $virgula." rh98_sequencial = $this->rh98_sequencial ";
       $virgula = ",";
       if(trim($this->rh98_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh98_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh98_rhdirfgeracaodadospessoal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_rhdirfgeracaodadospessoal"])){ 
       $sql  .= $virgula." rh98_rhdirfgeracaodadospessoal = $this->rh98_rhdirfgeracaodadospessoal ";
       $virgula = ",";
       if(trim($this->rh98_rhdirfgeracaodadospessoal) == null ){ 
         $this->erro_sql = " Campo Dirf Dados Pessoais nao Informado.";
         $this->erro_campo = "rh98_rhdirfgeracaodadospessoal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh98_rhdirftipovalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_rhdirftipovalor"])){ 
       $sql  .= $virgula." rh98_rhdirftipovalor = $this->rh98_rhdirftipovalor ";
       $virgula = ",";
       if(trim($this->rh98_rhdirftipovalor) == null ){ 
         $this->erro_sql = " Campo Tipo de Valor nao Informado.";
         $this->erro_campo = "rh98_rhdirftipovalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh98_tipoirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_tipoirrf"])){ 
       $sql  .= $virgula." rh98_tipoirrf = '$this->rh98_tipoirrf' ";
       $virgula = ",";
       if(trim($this->rh98_tipoirrf) == null ){ 
         $this->erro_sql = " Campo Tipo Irrf nao Informado.";
         $this->erro_campo = "rh98_tipoirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh98_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_mes"])){ 
       $sql  .= $virgula." rh98_mes = $this->rh98_mes ";
       $virgula = ",";
       if(trim($this->rh98_mes) == null ){ 
         $this->erro_sql = " Campo M�s nao Informado.";
         $this->erro_campo = "rh98_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh98_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_valor"])){ 
       $sql  .= $virgula." rh98_valor = $this->rh98_valor ";
       $virgula = ",";
       if(trim($this->rh98_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh98_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh98_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh98_instit"])){ 
       $sql  .= $virgula." rh98_instit = $this->rh98_instit ";
       $virgula = ",";
       if(trim($this->rh98_instit) == null ){ 
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "rh98_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh98_sequencial!=null){
       $sql .= " rh98_sequencial = $this->rh98_sequencial";
     }
     if (!isset($_SESSION["ignoreAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->rh98_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17769,'$this->rh98_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_sequencial"]) || $this->rh98_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3139,17769,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_sequencial'))."','$this->rh98_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_rhdirfgeracaodadospessoal"]) || $this->rh98_rhdirfgeracaodadospessoal != "")
             $resac = db_query("insert into db_acount values($acount,3139,17771,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_rhdirfgeracaodadospessoal'))."','$this->rh98_rhdirfgeracaodadospessoal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_rhdirftipovalor"]) || $this->rh98_rhdirftipovalor != "")
             $resac = db_query("insert into db_acount values($acount,3139,17781,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_rhdirftipovalor'))."','$this->rh98_rhdirftipovalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_tipoirrf"]) || $this->rh98_tipoirrf != "")
             $resac = db_query("insert into db_acount values($acount,3139,17777,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_tipoirrf'))."','$this->rh98_tipoirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_mes"]) || $this->rh98_mes != "")
             $resac = db_query("insert into db_acount values($acount,3139,17778,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_mes'))."','$this->rh98_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_valor"]) || $this->rh98_valor != "")
             $resac = db_query("insert into db_acount values($acount,3139,17779,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_valor'))."','$this->rh98_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh98_instit"]) || $this->rh98_instit != "")
             $resac = db_query("insert into db_acount values($acount,3139,17805,'".AddSlashes(pg_result($resaco,$conresaco,'rh98_instit'))."','$this->rh98_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if ($result==false) {
        
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaodadospessoalvalor nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh98_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaodadospessoalvalor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh98_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh98_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh98_sequencial=null,$dbwhere=null) {

     if (!isset($_SESSION["ignoreAccount"])) {
       if($dbwhere==null || $dbwhere==""){
         
         
         $resaco = $this->sql_record($this->sql_query_file($rh98_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17769,'$rh98_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3139,17769,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3139,17771,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_rhdirfgeracaodadospessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3139,17781,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_rhdirftipovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3139,17777,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_tipoirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3139,17778,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3139,17779,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3139,17805,'','".AddSlashes(pg_result($resaco,$iresaco,'rh98_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhdirfgeracaodadospessoalvalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh98_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh98_sequencial = $rh98_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaodadospessoalvalor nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh98_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaodadospessoalvalor nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh98_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh98_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracaodadospessoalvalor";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdirfgeracaodadospessoalvalor ";
     $sql .= "      inner join rhdirfgeracaodadospessoal  on  rhdirfgeracaodadospessoal.rh96_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirfgeracaodadospessoal";
     $sql .= "      inner join rhdirftipovalor  on  rhdirftipovalor.rh97_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirftipovalor";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm";
     $sql .= "      inner join rhdirfgeracao  on  rhdirfgeracao.rh95_sequencial = rhdirfgeracaodadospessoal.rh96_rhdirfgeracao";
     $sql2 = "";
     if($dbwhere==""){
       if($rh98_sequencial!=null ){
         $sql2 .= " where rhdirfgeracaodadospessoalvalor.rh98_sequencial = $rh98_sequencial "; 
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
   function sql_query_file ( $rh98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdirfgeracaodadospessoalvalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh98_sequencial!=null ){
         $sql2 .= " where rhdirfgeracaodadospessoalvalor.rh98_sequencial = $rh98_sequencial "; 
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