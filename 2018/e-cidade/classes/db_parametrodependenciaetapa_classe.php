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

//MODULO: escola
//CLASSE DA ENTIDADE parametrodependenciaetapa
class cl_parametrodependenciaetapa { 
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
   var $ed296_sequencial = 0; 
   var $ed296_parametrodependencia = 0; 
   var $ed296_etapa = 0; 
   var $ed296_cursoedu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed296_sequencial = int4 = Sequencial 
                 ed296_parametrodependencia = int4 = Parametro Dependencia 
                 ed296_etapa = int4 = Etapa 
                 ed296_cursoedu = int4 = Cursos com Progressão Parcial 
                 ";
   //funcao construtor da classe 
   function cl_parametrodependenciaetapa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parametrodependenciaetapa"); 
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
       $this->ed296_sequencial = ($this->ed296_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed296_sequencial"]:$this->ed296_sequencial);
       $this->ed296_parametrodependencia = ($this->ed296_parametrodependencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed296_parametrodependencia"]:$this->ed296_parametrodependencia);
       $this->ed296_etapa = ($this->ed296_etapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed296_etapa"]:$this->ed296_etapa);
       $this->ed296_cursoedu = ($this->ed296_cursoedu == ""?@$GLOBALS["HTTP_POST_VARS"]["ed296_cursoedu"]:$this->ed296_cursoedu);
     }else{
       $this->ed296_sequencial = ($this->ed296_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed296_sequencial"]:$this->ed296_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed296_sequencial){ 
      $this->atualizacampos();
     if($this->ed296_parametrodependencia == null ){ 
       $this->erro_sql = " Campo Parametro Dependencia nao Informado.";
       $this->erro_campo = "ed296_parametrodependencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed296_etapa == null ){ 
       $this->erro_sql = " Campo Etapa nao Informado.";
       $this->erro_campo = "ed296_etapa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed296_cursoedu == null ){ 
       $this->erro_sql = " Campo Cursos com Progressão Parcial nao Informado.";
       $this->erro_campo = "ed296_cursoedu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed296_sequencial == "" || $ed296_sequencial == null ){
       $result = db_query("select nextval('parametrodependenciaetapa_ed296_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: parametrodependenciaetapa_ed296_sequencial_seq do campo: ed296_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed296_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from parametrodependenciaetapa_ed296_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed296_sequencial)){
         $this->erro_sql = " Campo ed296_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed296_sequencial = $ed296_sequencial; 
       }
     }
     if(($this->ed296_sequencial == null) || ($this->ed296_sequencial == "") ){ 
       $this->erro_sql = " Campo ed296_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parametrodependenciaetapa(
                                       ed296_sequencial 
                                      ,ed296_parametrodependencia 
                                      ,ed296_etapa 
                                      ,ed296_cursoedu 
                       )
                values (
                                $this->ed296_sequencial 
                               ,$this->ed296_parametrodependencia 
                               ,$this->ed296_etapa 
                               ,$this->ed296_cursoedu 
                      )";
     $result = db_query($sql);      
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "parametro dependencia etapa ($this->ed296_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "parametro dependencia etapa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "parametro dependencia etapa ($this->ed296_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed296_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed296_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18522,'$this->ed296_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3276,18522,'','".AddSlashes(pg_result($resaco,0,'ed296_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3276,18523,'','".AddSlashes(pg_result($resaco,0,'ed296_parametrodependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3276,18524,'','".AddSlashes(pg_result($resaco,0,'ed296_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3276,18525,'','".AddSlashes(pg_result($resaco,0,'ed296_cursoedu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed296_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update parametrodependenciaetapa set ";
     $virgula = "";
     if(trim($this->ed296_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed296_sequencial"])){ 
       $sql  .= $virgula." ed296_sequencial = $this->ed296_sequencial ";
       $virgula = ",";
       if(trim($this->ed296_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ed296_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed296_parametrodependencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed296_parametrodependencia"])){ 
       $sql  .= $virgula." ed296_parametrodependencia = $this->ed296_parametrodependencia ";
       $virgula = ",";
       if(trim($this->ed296_parametrodependencia) == null ){ 
         $this->erro_sql = " Campo Parametro Dependencia nao Informado.";
         $this->erro_campo = "ed296_parametrodependencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed296_etapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed296_etapa"])){ 
       $sql  .= $virgula." ed296_etapa = $this->ed296_etapa ";
       $virgula = ",";
       if(trim($this->ed296_etapa) == null ){ 
         $this->erro_sql = " Campo Etapa nao Informado.";
         $this->erro_campo = "ed296_etapa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed296_cursoedu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed296_cursoedu"])){ 
       $sql  .= $virgula." ed296_cursoedu = $this->ed296_cursoedu ";
       $virgula = ",";
       if(trim($this->ed296_cursoedu) == null ){ 
         $this->erro_sql = " Campo Cursos com Progressão Parcial nao Informado.";
         $this->erro_campo = "ed296_cursoedu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed296_sequencial!=null){
       $sql .= " ed296_sequencial = $this->ed296_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed296_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18522,'$this->ed296_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed296_sequencial"]) || $this->ed296_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3276,18522,'".AddSlashes(pg_result($resaco,$conresaco,'ed296_sequencial'))."','$this->ed296_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed296_parametrodependencia"]) || $this->ed296_parametrodependencia != "")
           $resac = db_query("insert into db_acount values($acount,3276,18523,'".AddSlashes(pg_result($resaco,$conresaco,'ed296_parametrodependencia'))."','$this->ed296_parametrodependencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed296_etapa"]) || $this->ed296_etapa != "")
           $resac = db_query("insert into db_acount values($acount,3276,18524,'".AddSlashes(pg_result($resaco,$conresaco,'ed296_etapa'))."','$this->ed296_etapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed296_cursoedu"]) || $this->ed296_cursoedu != "")
           $resac = db_query("insert into db_acount values($acount,3276,18525,'".AddSlashes(pg_result($resaco,$conresaco,'ed296_cursoedu'))."','$this->ed296_cursoedu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parametro dependencia etapa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed296_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "parametro dependencia etapa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed296_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed296_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed296_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed296_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18522,'$ed296_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3276,18522,'','".AddSlashes(pg_result($resaco,$iresaco,'ed296_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3276,18523,'','".AddSlashes(pg_result($resaco,$iresaco,'ed296_parametrodependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3276,18524,'','".AddSlashes(pg_result($resaco,$iresaco,'ed296_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3276,18525,'','".AddSlashes(pg_result($resaco,$iresaco,'ed296_cursoedu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from parametrodependenciaetapa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed296_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed296_sequencial = $ed296_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parametro dependencia etapa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed296_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "parametro dependencia etapa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed296_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed296_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:parametrodependenciaetapa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed296_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parametrodependenciaetapa ";
     $sql .= "      inner join parametrodependencia  on  parametrodependencia.ed295_sequencial = parametrodependenciaetapa.ed296_parametrodependencia";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = parametrodependenciaetapa.ed296_etapa";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = parametrodependenciaetapa.ed296_cursoedu";
     $sql .= "      left join escola  on  escola.ed18_i_codigo = parametrodependencia.ed295_escola";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join ensino  as a on   a.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed296_sequencial!=null ){
         $sql2 .= " where parametrodependenciaetapa.ed296_sequencial = $ed296_sequencial "; 
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
   function sql_query_file ( $ed296_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parametrodependenciaetapa ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed296_sequencial!=null ){
         $sql2 .= " where parametrodependenciaetapa.ed296_sequencial = $ed296_sequencial "; 
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