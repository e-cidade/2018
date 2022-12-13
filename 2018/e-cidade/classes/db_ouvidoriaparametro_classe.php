<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriaparametro
class cl_ouvidoriaparametro { 
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
   var $ov06_instit = 0; 
   var $ov06_anousu = 0; 
   var $ov06_tiponumprocesso = 0; 
   var $ov06_db_documentotemplate = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov06_instit = int4 = Instituíção 
                 ov06_anousu = int4 = Ano 
                 ov06_tiponumprocesso = int4 = Numeração do atendimento de ouvidoria 
                 ov06_db_documentotemplate = int4 = Documento Template 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriaparametro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriaparametro"); 
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
       $this->ov06_instit = ($this->ov06_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ov06_instit"]:$this->ov06_instit);
       $this->ov06_anousu = ($this->ov06_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ov06_anousu"]:$this->ov06_anousu);
       $this->ov06_tiponumprocesso = ($this->ov06_tiponumprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ov06_tiponumprocesso"]:$this->ov06_tiponumprocesso);
       $this->ov06_db_documentotemplate = ($this->ov06_db_documentotemplate == ""?@$GLOBALS["HTTP_POST_VARS"]["ov06_db_documentotemplate"]:$this->ov06_db_documentotemplate);
     }else{
       $this->ov06_instit = ($this->ov06_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ov06_instit"]:$this->ov06_instit);
       $this->ov06_anousu = ($this->ov06_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ov06_anousu"]:$this->ov06_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($ov06_instit,$ov06_anousu){ 
      $this->atualizacampos();
     if($this->ov06_tiponumprocesso == null ){ 
       $this->erro_sql = " Campo Numeração do atendimento de ouvidoria nao Informado.";
       $this->erro_campo = "ov06_tiponumprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov06_db_documentotemplate == null ){ 
       $this->ov06_db_documentotemplate = "null";
     }
       $this->ov06_instit = $ov06_instit; 
       $this->ov06_anousu = $ov06_anousu; 
     if(($this->ov06_instit == null) || ($this->ov06_instit == "") ){ 
       $this->erro_sql = " Campo ov06_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->ov06_anousu == null) || ($this->ov06_anousu == "") ){ 
       $this->erro_sql = " Campo ov06_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriaparametro(
                                       ov06_instit 
                                      ,ov06_anousu 
                                      ,ov06_tiponumprocesso 
                                      ,ov06_db_documentotemplate 
                       )
                values (
                                $this->ov06_instit 
                               ,$this->ov06_anousu 
                               ,$this->ov06_tiponumprocesso 
                               ,$this->ov06_db_documentotemplate 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de Parametros ouvidoria ($this->ov06_instit."-".$this->ov06_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de Parametros ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de Parametros ouvidoria ($this->ov06_instit."-".$this->ov06_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov06_instit."-".$this->ov06_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov06_instit,$this->ov06_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14714,'$this->ov06_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,14715,'$this->ov06_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,2588,14714,'','".AddSlashes(pg_result($resaco,0,'ov06_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2588,14715,'','".AddSlashes(pg_result($resaco,0,'ov06_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2588,14719,'','".AddSlashes(pg_result($resaco,0,'ov06_tiponumprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2588,18934,'','".AddSlashes(pg_result($resaco,0,'ov06_db_documentotemplate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov06_instit=null,$ov06_anousu=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriaparametro set ";
     $virgula = "";
     if(trim($this->ov06_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov06_instit"])){ 
       $sql  .= $virgula." ov06_instit = $this->ov06_instit ";
       $virgula = ",";
       if(trim($this->ov06_instit) == null ){ 
         $this->erro_sql = " Campo Instituíção nao Informado.";
         $this->erro_campo = "ov06_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov06_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov06_anousu"])){ 
       $sql  .= $virgula." ov06_anousu = $this->ov06_anousu ";
       $virgula = ",";
       if(trim($this->ov06_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "ov06_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov06_tiponumprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov06_tiponumprocesso"])){ 
       $sql  .= $virgula." ov06_tiponumprocesso = $this->ov06_tiponumprocesso ";
       $virgula = ",";
       if(trim($this->ov06_tiponumprocesso) == null ){ 
         $this->erro_sql = " Campo Numeração do atendimento de ouvidoria nao Informado.";
         $this->erro_campo = "ov06_tiponumprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov06_db_documentotemplate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov06_db_documentotemplate"])){ 
        if(trim($this->ov06_db_documentotemplate)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ov06_db_documentotemplate"])){ 
           $this->ov06_db_documentotemplate = "null" ; 
        } 
       $sql  .= $virgula." ov06_db_documentotemplate = $this->ov06_db_documentotemplate ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ov06_instit!=null){
       $sql .= " ov06_instit = $this->ov06_instit";
     }
     if($ov06_anousu!=null){
       $sql .= " and  ov06_anousu = $this->ov06_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov06_instit,$this->ov06_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14714,'$this->ov06_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,14715,'$this->ov06_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov06_instit"]) || $this->ov06_instit != "")
           $resac = db_query("insert into db_acount values($acount,2588,14714,'".AddSlashes(pg_result($resaco,$conresaco,'ov06_instit'))."','$this->ov06_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov06_anousu"]) || $this->ov06_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2588,14715,'".AddSlashes(pg_result($resaco,$conresaco,'ov06_anousu'))."','$this->ov06_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov06_tiponumprocesso"]) || $this->ov06_tiponumprocesso != "")
           $resac = db_query("insert into db_acount values($acount,2588,14719,'".AddSlashes(pg_result($resaco,$conresaco,'ov06_tiponumprocesso'))."','$this->ov06_tiponumprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov06_db_documentotemplate"]) || $this->ov06_db_documentotemplate != "")
           $resac = db_query("insert into db_acount values($acount,2588,18934,'".AddSlashes(pg_result($resaco,$conresaco,'ov06_db_documentotemplate'))."','$this->ov06_db_documentotemplate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Parametros ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov06_instit."-".$this->ov06_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Parametros ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov06_instit."-".$this->ov06_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov06_instit."-".$this->ov06_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov06_instit=null,$ov06_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov06_instit,$ov06_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14714,'$ov06_instit','E')");
         $resac = db_query("insert into db_acountkey values($acount,14715,'$ov06_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,2588,14714,'','".AddSlashes(pg_result($resaco,$iresaco,'ov06_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2588,14715,'','".AddSlashes(pg_result($resaco,$iresaco,'ov06_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2588,14719,'','".AddSlashes(pg_result($resaco,$iresaco,'ov06_tiponumprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2588,18934,'','".AddSlashes(pg_result($resaco,$iresaco,'ov06_db_documentotemplate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriaparametro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov06_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov06_instit = $ov06_instit ";
        }
        if($ov06_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov06_anousu = $ov06_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Parametros ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov06_instit."-".$ov06_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Parametros ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov06_instit."-".$ov06_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov06_instit."-".$ov06_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriaparametro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov06_instit=null,$ov06_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaparametro ";
     $sql .= "      left  join db_documentotemplate  on  db_documentotemplate.db82_sequencial = ouvidoriaparametro.ov06_db_documentotemplate";
     $sql .= "      inner join db_config  on  db_config.codigo = db_documentotemplate.db82_instit";
     $sql .= "      inner join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ov06_instit!=null ){
         $sql2 .= " where ouvidoriaparametro.ov06_instit = $ov06_instit "; 
       } 
       if($ov06_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ouvidoriaparametro.ov06_anousu = $ov06_anousu "; 
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
   function sql_query_file ( $ov06_instit=null,$ov06_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaparametro ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov06_instit!=null ){
         $sql2 .= " where ouvidoriaparametro.ov06_instit = $ov06_instit "; 
       } 
       if($ov06_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ouvidoriaparametro.ov06_anousu = $ov06_anousu "; 
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