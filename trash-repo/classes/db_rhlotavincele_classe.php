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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhlotavincele
class cl_rhlotavincele { 
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
   var $rh28_codlotavinc = 0; 
   var $rh28_codeledef = 0; 
   var $rh28_codelenov = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh28_codlotavinc = int8 = Código 
                 rh28_codeledef = int4 = Elemento principal 
                 rh28_codelenov = int4 = Elemento novo 
                 ";
   //funcao construtor da classe 
   function cl_rhlotavincele() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhlotavincele"); 
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
       $this->rh28_codlotavinc = ($this->rh28_codlotavinc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh28_codlotavinc"]:$this->rh28_codlotavinc);
       $this->rh28_codeledef = ($this->rh28_codeledef == ""?@$GLOBALS["HTTP_POST_VARS"]["rh28_codeledef"]:$this->rh28_codeledef);
       $this->rh28_codelenov = ($this->rh28_codelenov == ""?@$GLOBALS["HTTP_POST_VARS"]["rh28_codelenov"]:$this->rh28_codelenov);
     }else{
       $this->rh28_codlotavinc = ($this->rh28_codlotavinc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh28_codlotavinc"]:$this->rh28_codlotavinc);
       $this->rh28_codeledef = ($this->rh28_codeledef == ""?@$GLOBALS["HTTP_POST_VARS"]["rh28_codeledef"]:$this->rh28_codeledef);
     }
   }
   // funcao para inclusao
   function incluir ($rh28_codlotavinc,$rh28_codeledef){ 
      $this->atualizacampos();
     if($this->rh28_codelenov == null ){ 
       $this->erro_sql = " Campo Elemento novo nao Informado.";
       $this->erro_campo = "rh28_codelenov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh28_codlotavinc = $rh28_codlotavinc; 
       $this->rh28_codeledef = $rh28_codeledef; 
     if(($this->rh28_codlotavinc == null) || ($this->rh28_codlotavinc == "") ){ 
       $this->erro_sql = " Campo rh28_codlotavinc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh28_codeledef == null) || ($this->rh28_codeledef == "") ){ 
       $this->erro_sql = " Campo rh28_codeledef nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlotavincele(
                                       rh28_codlotavinc 
                                      ,rh28_codeledef 
                                      ,rh28_codelenov 
                       )
                values (
                                $this->rh28_codlotavinc 
                               ,$this->rh28_codeledef 
                               ,$this->rh28_codelenov 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->rh28_codlotavinc."-".$this->rh28_codeledef) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->rh28_codlotavinc."-".$this->rh28_codeledef) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh28_codlotavinc."-".$this->rh28_codeledef;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh28_codlotavinc,$this->rh28_codeledef));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7143,'$this->rh28_codlotavinc','I')");
       $resac = db_query("insert into db_acountkey values($acount,7144,'$this->rh28_codeledef','I')");
       $resac = db_query("insert into db_acount values($acount,1184,7143,'','".AddSlashes(pg_result($resaco,0,'rh28_codlotavinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1184,7144,'','".AddSlashes(pg_result($resaco,0,'rh28_codeledef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1184,7145,'','".AddSlashes(pg_result($resaco,0,'rh28_codelenov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh28_codlotavinc=null,$rh28_codeledef=null) { 
      $this->atualizacampos();
     $sql = " update rhlotavincele set ";
     $virgula = "";
     if(trim($this->rh28_codlotavinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh28_codlotavinc"])){ 
       $sql  .= $virgula." rh28_codlotavinc = $this->rh28_codlotavinc ";
       $virgula = ",";
       if(trim($this->rh28_codlotavinc) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "rh28_codlotavinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh28_codeledef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh28_codeledef"])){ 
       $sql  .= $virgula." rh28_codeledef = $this->rh28_codeledef ";
       $virgula = ",";
       if(trim($this->rh28_codeledef) == null ){ 
         $this->erro_sql = " Campo Elemento principal nao Informado.";
         $this->erro_campo = "rh28_codeledef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh28_codelenov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh28_codelenov"])){ 
       $sql  .= $virgula." rh28_codelenov = $this->rh28_codelenov ";
       $virgula = ",";
       if(trim($this->rh28_codelenov) == null ){ 
         $this->erro_sql = " Campo Elemento novo nao Informado.";
         $this->erro_campo = "rh28_codelenov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh28_codlotavinc!=null){
       $sql .= " rh28_codlotavinc = $this->rh28_codlotavinc";
     }
     if($rh28_codeledef!=null){
       $sql .= " and  rh28_codeledef = $this->rh28_codeledef";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh28_codlotavinc,$this->rh28_codeledef));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7143,'$this->rh28_codlotavinc','A')");
         $resac = db_query("insert into db_acountkey values($acount,7144,'$this->rh28_codeledef','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh28_codlotavinc"]))
           $resac = db_query("insert into db_acount values($acount,1184,7143,'".AddSlashes(pg_result($resaco,$conresaco,'rh28_codlotavinc'))."','$this->rh28_codlotavinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh28_codeledef"]))
           $resac = db_query("insert into db_acount values($acount,1184,7144,'".AddSlashes(pg_result($resaco,$conresaco,'rh28_codeledef'))."','$this->rh28_codeledef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh28_codelenov"]))
           $resac = db_query("insert into db_acount values($acount,1184,7145,'".AddSlashes(pg_result($resaco,$conresaco,'rh28_codelenov'))."','$this->rh28_codelenov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh28_codlotavinc."-".$this->rh28_codeledef;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh28_codlotavinc."-".$this->rh28_codeledef;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh28_codlotavinc."-".$this->rh28_codeledef;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh28_codlotavinc=null,$rh28_codeledef=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh28_codlotavinc,$rh28_codeledef));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7143,'$rh28_codlotavinc','E')");
         $resac = db_query("insert into db_acountkey values($acount,7144,'$rh28_codeledef','E')");
         $resac = db_query("insert into db_acount values($acount,1184,7143,'','".AddSlashes(pg_result($resaco,$iresaco,'rh28_codlotavinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1184,7144,'','".AddSlashes(pg_result($resaco,$iresaco,'rh28_codeledef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1184,7145,'','".AddSlashes(pg_result($resaco,$iresaco,'rh28_codelenov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhlotavincele
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh28_codlotavinc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh28_codlotavinc = $rh28_codlotavinc ";
        }
        if($rh28_codeledef != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh28_codeledef = $rh28_codeledef ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh28_codlotavinc."-".$rh28_codeledef;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh28_codlotavinc."-".$rh28_codeledef;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh28_codlotavinc."-".$rh28_codeledef;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlotavincele";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query_ele ( $rh28_codlotavinc=null,$rh28_codeledef=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavincele ";
     $sql .= "      inner join orcelemento    on  orcelemento.o56_codele = rhlotavincele.rh28_codeledef and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcelemento a  on  a.o56_codele = rhlotavincele.rh28_codelenov";
     $sql .= "       left join rhlotavincativ on rhlotavincativ.rh39_codlotavinc = rhlotavincele.rh28_codlotavinc
                                             and rhlotavincativ.rh39_codelenov=rhlotavincele.rh28_codelenov";
     $sql .= "       left join orcprojativ    on orcprojativ.o55_anousu = rhlotavincativ.rh39_anousu
                                             and orcprojativ.o55_projativ = rhlotavincativ.rh39_projativ";
     $sql .= "       left join rhlotavincrec  on rhlotavincrec.rh43_codlotavinc = rhlotavincele.rh28_codlotavinc " ;
     $sql .= "                                and rhlotavincrec.rh43_codelenov = rhlotavincele.rh28_codelenov ";
     $sql .= "       left join orctiporec     on orctiporec.o15_codigo = rhlotavincrec.rh43_recurso " ;

     $sql .= "      left  join orcfuncao      on  orcfuncao.o52_funcao       = rhlotavincativ.rh39_funcao      ";
     $sql .= "      left  join orcsubfuncao   on  orcsubfuncao.o53_subfuncao = rhlotavincativ.rh39_subfuncao   ";
	   $sql .= "      left  join orcprograma    on  orcprograma.o54_anousu     = rhlotavincativ.rh39_anousu      ";
	   $sql .= "								               and  orcprograma.o54_programa   = rhlotavincativ.rh39_programa    ";

		 /*
     $sql .= "      inner join orcdotacao on orcdotacao.o58_codele = orcelemento.o56_codele";
     $sql .= "      inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu
                                          and orcprojativ.o55_projativ = orcdotacao.o58_projativ";
		 */

     $sql2 = "";
     if($dbwhere==""){
       if($rh28_codlotavinc!=null ){
         $sql2 .= " where rhlotavincele.rh28_codlotavinc = $rh28_codlotavinc "; 
       } 
       if($rh28_codeledef!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotavincele.rh28_codeledef = $rh28_codeledef "; 
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
   function sql_query ( $rh28_codlotavinc=null,$rh28_codeledef=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavincele ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhlotavincele.rh28_codeledef and orcelemento.o56_codele=".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($rh28_codlotavinc!=null ){
         $sql2 .= " where rhlotavincele.rh28_codlotavinc = $rh28_codlotavinc "; 
       } 
       if($rh28_codeledef!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotavincele.rh28_codeledef = $rh28_codeledef "; 
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
   function sql_query_file ( $rh28_codlotavinc=null,$rh28_codeledef=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavincele ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh28_codlotavinc!=null ){
         $sql2 .= " where rhlotavincele.rh28_codlotavinc = $rh28_codlotavinc "; 
       } 
       if($rh28_codeledef!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotavincele.rh28_codeledef = $rh28_codeledef "; 
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