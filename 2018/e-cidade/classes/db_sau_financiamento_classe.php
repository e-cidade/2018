<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: saude
//CLASSE DA ENTIDADE sau_financiamento
class cl_sau_financiamento { 
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
   var $sd65_i_codigo = 0; 
   var $sd65_c_financiamento = null; 
   var $sd65_c_nome = null; 
   var $sd65_i_anocomp = 0; 
   var $sd65_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd65_i_codigo = int4 = C�digo 
                 sd65_c_financiamento = varchar(2) = Financiamento 
                 sd65_c_nome = varchar(100) = Nome 
                 sd65_i_anocomp = int4 = Ano 
                 sd65_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_financiamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_financiamento"); 
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
       $this->sd65_i_codigo = ($this->sd65_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd65_i_codigo"]:$this->sd65_i_codigo);
       $this->sd65_c_financiamento = ($this->sd65_c_financiamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd65_c_financiamento"]:$this->sd65_c_financiamento);
       $this->sd65_c_nome = ($this->sd65_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["sd65_c_nome"]:$this->sd65_c_nome);
       $this->sd65_i_anocomp = ($this->sd65_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd65_i_anocomp"]:$this->sd65_i_anocomp);
       $this->sd65_i_mescomp = ($this->sd65_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd65_i_mescomp"]:$this->sd65_i_mescomp);
     }else{
       $this->sd65_i_codigo = ($this->sd65_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd65_i_codigo"]:$this->sd65_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd65_i_codigo){ 
      $this->atualizacampos();
     if($this->sd65_c_financiamento == null ){ 
       $this->erro_sql = " Campo Financiamento nao Informado.";
       $this->erro_campo = "sd65_c_financiamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd65_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "sd65_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd65_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd65_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd65_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd65_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd65_i_codigo == "" || $sd65_i_codigo == null ){
       $result = db_query("select nextval('sau_financiamento_sd65_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_financiamento_sd65_i_codigo_seq do campo: sd65_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd65_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_financiamento_sd65_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd65_i_codigo)){
         $this->erro_sql = " Campo sd65_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd65_i_codigo = $sd65_i_codigo; 
       }
     }
     if(($this->sd65_i_codigo == null) || ($this->sd65_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd65_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_financiamento(
                                       sd65_i_codigo 
                                      ,sd65_c_financiamento 
                                      ,sd65_c_nome 
                                      ,sd65_i_anocomp 
                                      ,sd65_i_mescomp 
                       )
                values (
                                $this->sd65_i_codigo 
                               ,'$this->sd65_c_financiamento' 
                               ,'$this->sd65_c_nome' 
                               ,$this->sd65_i_anocomp 
                               ,$this->sd65_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_financiamento ($this->sd65_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_financiamento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_financiamento ($this->sd65_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd65_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd65_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11474,'$this->sd65_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1974,11474,'','".AddSlashes(pg_result($resaco,0,'sd65_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1974,11475,'','".AddSlashes(pg_result($resaco,0,'sd65_c_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1974,11476,'','".AddSlashes(pg_result($resaco,0,'sd65_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1974,11477,'','".AddSlashes(pg_result($resaco,0,'sd65_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1974,11478,'','".AddSlashes(pg_result($resaco,0,'sd65_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd65_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_financiamento set ";
     $virgula = "";
     if(trim($this->sd65_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd65_i_codigo"])){ 
       $sql  .= $virgula." sd65_i_codigo = $this->sd65_i_codigo ";
       $virgula = ",";
       if(trim($this->sd65_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "sd65_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd65_c_financiamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd65_c_financiamento"])){ 
       $sql  .= $virgula." sd65_c_financiamento = '$this->sd65_c_financiamento' ";
       $virgula = ",";
       if(trim($this->sd65_c_financiamento) == null ){ 
         $this->erro_sql = " Campo Financiamento nao Informado.";
         $this->erro_campo = "sd65_c_financiamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd65_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd65_c_nome"])){ 
       $sql  .= $virgula." sd65_c_nome = '$this->sd65_c_nome' ";
       $virgula = ",";
       if(trim($this->sd65_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "sd65_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd65_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd65_i_anocomp"])){ 
       $sql  .= $virgula." sd65_i_anocomp = $this->sd65_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd65_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd65_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd65_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd65_i_mescomp"])){ 
       $sql  .= $virgula." sd65_i_mescomp = $this->sd65_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd65_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd65_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd65_i_codigo!=null){
       $sql .= " sd65_i_codigo = $this->sd65_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd65_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11474,'$this->sd65_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd65_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1974,11474,'".AddSlashes(pg_result($resaco,$conresaco,'sd65_i_codigo'))."','$this->sd65_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd65_c_financiamento"]))
           $resac = db_query("insert into db_acount values($acount,1974,11475,'".AddSlashes(pg_result($resaco,$conresaco,'sd65_c_financiamento'))."','$this->sd65_c_financiamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd65_c_nome"]))
           $resac = db_query("insert into db_acount values($acount,1974,11476,'".AddSlashes(pg_result($resaco,$conresaco,'sd65_c_nome'))."','$this->sd65_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd65_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,1974,11477,'".AddSlashes(pg_result($resaco,$conresaco,'sd65_i_anocomp'))."','$this->sd65_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd65_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,1974,11478,'".AddSlashes(pg_result($resaco,$conresaco,'sd65_i_mescomp'))."','$this->sd65_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_financiamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd65_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_financiamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd65_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd65_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd65_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd65_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11474,'$sd65_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1974,11474,'','".AddSlashes(pg_result($resaco,$iresaco,'sd65_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1974,11475,'','".AddSlashes(pg_result($resaco,$iresaco,'sd65_c_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1974,11476,'','".AddSlashes(pg_result($resaco,$iresaco,'sd65_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1974,11477,'','".AddSlashes(pg_result($resaco,$iresaco,'sd65_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1974,11478,'','".AddSlashes(pg_result($resaco,$iresaco,'sd65_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_financiamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd65_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd65_i_codigo = $sd65_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_financiamento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd65_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_financiamento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd65_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd65_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_financiamento";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd65_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_financiamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd65_i_codigo!=null ){
         $sql2 .= " where sau_financiamento.sd65_i_codigo = $sd65_i_codigo "; 
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
   function sql_query_file ( $sd65_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_financiamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd65_i_codigo!=null ){
         $sql2 .= " where sau_financiamento.sd65_i_codigo = $sd65_i_codigo "; 
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
  
  function sql_query_ultimos_financiamentos( $sOrdem = null) {
    
    $sSql  = " select sd65_i_codigo        as codigo, ";
    $sSql .= "        sd65_c_financiamento as financiamento,";
    $sSql .= "        sd65_c_nome          as descricao";
    $sSql .= "   from sau_financiamento";
    $sSql .= "  where sd65_i_anocomp=(select max(sd65_i_anocomp) ";
    $sSql .= "  	                     from sau_financiamento) ";
    $sSql .= "    and sd65_i_mescomp=(select max(sd65_i_mescomp) ";
    $sSql .= "    	                     from sau_financiamento ";
    $sSql .= "    	                    where sd65_i_anocomp=(select max(sd65_i_anocomp) ";
    $sSql .= "    	                    	                    from sau_financiamento))";
    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }
  
}
?>