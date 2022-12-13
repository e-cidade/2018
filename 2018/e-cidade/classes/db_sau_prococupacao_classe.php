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

//MODULO: saude
//CLASSE DA ENTIDADE sau_prococupacao
class cl_sau_prococupacao { 
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
   var $sd90_i_codigo = 0; 
   var $sd90_i_procedimento = 0; 
   var $sd90_i_ocupacao = 0; 
   var $sd90_i_anocomp = 0; 
   var $sd90_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd90_i_codigo = int8 = C�digo 
                 sd90_i_procedimento = int8 = Procedimento 
                 sd90_i_ocupacao = int8 = Ocupa��o 
                 sd90_i_anocomp = int4 = Ano 
                 sd90_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_prococupacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_prococupacao"); 
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
       $this->sd90_i_codigo = ($this->sd90_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd90_i_codigo"]:$this->sd90_i_codigo);
       $this->sd90_i_procedimento = ($this->sd90_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd90_i_procedimento"]:$this->sd90_i_procedimento);
       $this->sd90_i_ocupacao = ($this->sd90_i_ocupacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd90_i_ocupacao"]:$this->sd90_i_ocupacao);
       $this->sd90_i_anocomp = ($this->sd90_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd90_i_anocomp"]:$this->sd90_i_anocomp);
       $this->sd90_i_mescomp = ($this->sd90_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd90_i_mescomp"]:$this->sd90_i_mescomp);
     }else{
       $this->sd90_i_codigo = ($this->sd90_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd90_i_codigo"]:$this->sd90_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd90_i_codigo){ 
      $this->atualizacampos();
     if($this->sd90_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd90_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd90_i_ocupacao == null ){ 
       $this->erro_sql = " Campo Ocupa��o nao Informado.";
       $this->erro_campo = "sd90_i_ocupacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd90_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd90_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd90_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd90_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd90_i_codigo == "" || $sd90_i_codigo == null ){
       $result = db_query("select nextval('sau_prococupacao_sd90_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_prococupacao_sd90_i_codigo_seq do campo: sd90_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd90_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_prococupacao_sd90_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd90_i_codigo)){
         $this->erro_sql = " Campo sd90_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd90_i_codigo = $sd90_i_codigo; 
       }
     }
     if(($this->sd90_i_codigo == null) || ($this->sd90_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd90_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_prococupacao(
                                       sd90_i_codigo 
                                      ,sd90_i_procedimento 
                                      ,sd90_i_ocupacao 
                                      ,sd90_i_anocomp 
                                      ,sd90_i_mescomp 
                       )
                values (
                                $this->sd90_i_codigo 
                               ,$this->sd90_i_procedimento 
                               ,$this->sd90_i_ocupacao 
                               ,$this->sd90_i_anocomp 
                               ,$this->sd90_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos e Ocupa��o ($this->sd90_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos e Ocupa��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos e Ocupa��o ($this->sd90_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd90_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd90_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11594,'$this->sd90_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1998,11594,'','".AddSlashes(pg_result($resaco,0,'sd90_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1998,11596,'','".AddSlashes(pg_result($resaco,0,'sd90_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1998,11595,'','".AddSlashes(pg_result($resaco,0,'sd90_i_ocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1998,11597,'','".AddSlashes(pg_result($resaco,0,'sd90_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1998,11598,'','".AddSlashes(pg_result($resaco,0,'sd90_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd90_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_prococupacao set ";
     $virgula = "";
     if(trim($this->sd90_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_codigo"])){ 
       $sql  .= $virgula." sd90_i_codigo = $this->sd90_i_codigo ";
       $virgula = ",";
       if(trim($this->sd90_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "sd90_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd90_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_procedimento"])){ 
       $sql  .= $virgula." sd90_i_procedimento = $this->sd90_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd90_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd90_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd90_i_ocupacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_ocupacao"])){ 
       $sql  .= $virgula." sd90_i_ocupacao = $this->sd90_i_ocupacao ";
       $virgula = ",";
       if(trim($this->sd90_i_ocupacao) == null ){ 
         $this->erro_sql = " Campo Ocupa��o nao Informado.";
         $this->erro_campo = "sd90_i_ocupacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd90_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_anocomp"])){ 
       $sql  .= $virgula." sd90_i_anocomp = $this->sd90_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd90_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd90_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd90_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_mescomp"])){ 
       $sql  .= $virgula." sd90_i_mescomp = $this->sd90_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd90_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd90_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd90_i_codigo!=null){
       $sql .= " sd90_i_codigo = $this->sd90_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd90_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11594,'$this->sd90_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1998,11594,'".AddSlashes(pg_result($resaco,$conresaco,'sd90_i_codigo'))."','$this->sd90_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,1998,11596,'".AddSlashes(pg_result($resaco,$conresaco,'sd90_i_procedimento'))."','$this->sd90_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_ocupacao"]))
           $resac = db_query("insert into db_acount values($acount,1998,11595,'".AddSlashes(pg_result($resaco,$conresaco,'sd90_i_ocupacao'))."','$this->sd90_i_ocupacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,1998,11597,'".AddSlashes(pg_result($resaco,$conresaco,'sd90_i_anocomp'))."','$this->sd90_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd90_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,1998,11598,'".AddSlashes(pg_result($resaco,$conresaco,'sd90_i_mescomp'))."','$this->sd90_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos e Ocupa��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd90_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos e Ocupa��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd90_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd90_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd90_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd90_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11594,'$sd90_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1998,11594,'','".AddSlashes(pg_result($resaco,$iresaco,'sd90_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1998,11596,'','".AddSlashes(pg_result($resaco,$iresaco,'sd90_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1998,11595,'','".AddSlashes(pg_result($resaco,$iresaco,'sd90_i_ocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1998,11597,'','".AddSlashes(pg_result($resaco,$iresaco,'sd90_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1998,11598,'','".AddSlashes(pg_result($resaco,$iresaco,'sd90_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_prococupacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd90_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd90_i_codigo = $sd90_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos e Ocupa��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd90_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos e Ocupa��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd90_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd90_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_prococupacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd90_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_prococupacao ";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_prococupacao.sd90_i_procedimento";
     $sql .= "      inner join sau_ocupacao  on  sau_ocupacao.sd89_c_codigo = sau_prococupacao.sd90_i_ocupacao";
     $sql .= "      left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd90_i_codigo!=null ){
         $sql2 .= " where sau_prococupacao.sd90_i_codigo = $sd90_i_codigo ";
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
   function sql_query_file ( $sd90_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_prococupacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd90_i_codigo!=null ){
         $sql2 .= " where sau_prococupacao.sd90_i_codigo = $sd90_i_codigo ";
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