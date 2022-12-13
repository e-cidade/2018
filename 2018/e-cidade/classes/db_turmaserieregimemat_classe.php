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
//CLASSE DA ENTIDADE turmaserieregimemat
class cl_turmaserieregimemat { 
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
   var $ed220_i_codigo = 0; 
   var $ed220_i_turma = 0;
   var $ed220_i_serieregimemat = 0; 
   var $ed220_c_historico = null;
   var $ed220_i_procedimento = 0;
   var $ed220_c_aprovauto = null;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed220_i_codigo = int8 = Código 
                 ed220_i_turma = int8 = Turma 
                 ed220_i_serieregimemat = int8 = Etapa 
                 ed220_c_historico = char(1) = Incluir no Histórico
                 ed220_i_procedimento = int8 = Proc. Avaliação
                 ed220_c_aprovauto = char(1) = Aprovação Automática 
                 ";
   //funcao construtor da classe 
   function cl_turmaserieregimemat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaserieregimemat"); 
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
       $this->ed220_i_codigo = ($this->ed220_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_i_codigo"]:$this->ed220_i_codigo);
       $this->ed220_i_turma = ($this->ed220_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_i_turma"]:$this->ed220_i_turma);
       $this->ed220_i_serieregimemat = ($this->ed220_i_serieregimemat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_i_serieregimemat"]:$this->ed220_i_serieregimemat);
       $this->ed220_c_historico = ($this->ed220_c_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_c_historico"]:$this->ed220_c_historico);
       $this->ed220_i_procedimento = ($this->ed220_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_i_procedimento"]:$this->ed220_i_procedimento);
       $this->ed220_c_aprovauto = ($this->ed220_c_aprovauto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_c_aprovauto"]:$this->ed220_c_aprovauto);
     }else{
       $this->ed220_i_codigo = ($this->ed220_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed220_i_codigo"]:$this->ed220_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed220_i_codigo){ 
      $this->atualizacampos();
     if($this->ed220_i_turma == null ){ 
       $this->erro_sql = " Campo Turma nao Informado.";
       $this->erro_campo = "ed220_i_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed220_i_serieregimemat == null ){ 
       $this->erro_sql = " Campo Etapa nao Informado.";
       $this->erro_campo = "ed220_i_serieregimemat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed220_c_historico == null ){ 
       $this->erro_sql = " Campo Incluir no Histórico nao Informado.";
       $this->erro_campo = "ed220_c_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed220_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento de Avaliação nao Informado.";
       $this->erro_campo = "ed220_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed220_c_aprovauto == null ){ 
       $this->erro_sql = " Campo Aprovação Automática nao Informado.";
       $this->erro_campo = "ed220_c_aprovauto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed220_i_codigo == "" || $ed220_i_codigo == null ){
       $result = db_query("select nextval('turmaserieregimemat_ed220_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaserieregimemat_ed220_i_codigo_seq do campo: ed220_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed220_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmaserieregimemat_ed220_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed220_i_codigo)){
         $this->erro_sql = " Campo ed220_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed220_i_codigo = $ed220_i_codigo; 
       }
     }
     if(($this->ed220_i_codigo == null) || ($this->ed220_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed220_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaserieregimemat(
                                       ed220_i_codigo 
                                      ,ed220_i_turma 
                                      ,ed220_i_serieregimemat 
                                      ,ed220_c_historico
                                      ,ed220_i_procedimento 
                                      ,ed220_c_aprovauto 
                       )
                values (
                                $this->ed220_i_codigo 
                               ,$this->ed220_i_turma 
                               ,$this->ed220_i_serieregimemat 
                               ,'$this->ed220_c_historico'
                               ,$this->ed220_i_procedimento 
                               ,'$this->ed220_c_aprovauto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Séries da Turma ($this->ed220_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Séries da Turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Séries da Turma ($this->ed220_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed220_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed220_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14962,'$this->ed220_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2627,14962,'','".AddSlashes(pg_result($resaco,0,'ed220_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2627,14963,'','".AddSlashes(pg_result($resaco,0,'ed220_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2627,14964,'','".AddSlashes(pg_result($resaco,0,'ed220_i_serieregimemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2627,14965,'','".AddSlashes(pg_result($resaco,0,'ed220_c_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2627,17207,'','".AddSlashes(pg_result($resaco,0,'ed220_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2627,17208,'','".AddSlashes(pg_result($resaco,0,'ed220_c_aprovauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed220_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmaserieregimemat set ";
     $virgula = "";
     if(trim($this->ed220_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_codigo"])){ 
       $sql  .= $virgula." ed220_i_codigo = $this->ed220_i_codigo ";
       $virgula = ",";
       if(trim($this->ed220_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed220_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed220_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_turma"])){ 
       $sql  .= $virgula." ed220_i_turma = $this->ed220_i_turma ";
       $virgula = ",";
       if(trim($this->ed220_i_turma) == null ){ 
         $this->erro_sql = " Campo Turma nao Informado.";
         $this->erro_campo = "ed220_i_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed220_i_serieregimemat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_serieregimemat"])){ 
       $sql  .= $virgula." ed220_i_serieregimemat = $this->ed220_i_serieregimemat ";
       $virgula = ",";
       if(trim($this->ed220_i_serieregimemat) == null ){ 
         $this->erro_sql = " Campo Etapa nao Informado.";
         $this->erro_campo = "ed220_i_serieregimemat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed220_c_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed220_c_historico"])){ 
       $sql  .= $virgula." ed220_c_historico = '$this->ed220_c_historico' ";
       $virgula = ",";
       if(trim($this->ed220_c_historico) == null ){ 
         $this->erro_sql = " Campo Incluir no Histórico nao Informado.";
         $this->erro_campo = "ed220_c_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed220_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_procedimento"])){ 
       $sql  .= $virgula." ed220_i_procedimento = $this->ed220_i_procedimento ";
       $virgula = ",";
       if(trim($this->ed220_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento de Avaliação nao Informado.";
         $this->erro_campo = "ed220_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed220_c_aprovauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed220_c_aprovauto"])){ 
       $sql  .= $virgula." ed220_c_aprovauto = '$this->ed220_c_aprovauto' ";
       $virgula = ",";
       if(trim($this->ed220_c_aprovauto) == null ){ 
         $this->erro_sql = " Campo Aprovação Automática nao Informado.";
         $this->erro_campo = "ed220_c_aprovauto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed220_i_codigo!=null){
       $sql .= " ed220_i_codigo = $this->ed220_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed220_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14962,'$this->ed220_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_codigo"]) || $this->ed220_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2627,14962,'".AddSlashes(pg_result($resaco,$conresaco,'ed220_i_codigo'))."','$this->ed220_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_turma"]) || $this->ed220_i_turma != "")
           $resac = db_query("insert into db_acount values($acount,2627,14963,'".AddSlashes(pg_result($resaco,$conresaco,'ed220_i_turma'))."','$this->ed220_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_serieregimemat"]) || $this->ed220_i_serieregimemat != "")
           $resac = db_query("insert into db_acount values($acount,2627,14964,'".AddSlashes(pg_result($resaco,$conresaco,'ed220_i_serieregimemat'))."','$this->ed220_i_serieregimemat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed220_c_historico"]) || $this->ed220_c_historico != "")
           $resac = db_query("insert into db_acount values($acount,2627,14965,'".AddSlashes(pg_result($resaco,$conresaco,'ed220_c_historico'))."','$this->ed220_c_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed220_i_procedimento"]) || $this->ed220_i_procedimento != "")
           $resac = db_query("insert into db_acount values($acount,2627,17207,'".AddSlashes(pg_result($resaco,$conresaco,'ed220_i_procedimento'))."','$this->ed220_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed220_c_aprovauto"]) || $this->ed220_c_aprovauto!= "")
           $resac = db_query("insert into db_acount values($acount,2627,17208,'".AddSlashes(pg_result($resaco,$conresaco,'ed220_c_aprovauto'))."','$this->ed220_c_aprovauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Séries da Turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed220_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Séries da Turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed220_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed220_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed220_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed220_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14962,'$ed220_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2627,14962,'','".AddSlashes(pg_result($resaco,$iresaco,'ed220_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2627,14963,'','".AddSlashes(pg_result($resaco,$iresaco,'ed220_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2627,14964,'','".AddSlashes(pg_result($resaco,$iresaco,'ed220_i_serieregimemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2627,14965,'','".AddSlashes(pg_result($resaco,$iresaco,'ed220_c_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2627,17207,'','".AddSlashes(pg_result($resaco,$iresaco,'ed220_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2627,17208,'','".AddSlashes(pg_result($resaco,$iresaco,'ed220_c_aprovauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmaserieregimemat
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed220_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed220_i_codigo = $ed220_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Séries da Turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed220_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Séries da Turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed220_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed220_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaserieregimemat";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed220_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaserieregimemat ";
     $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = serieregimemat.ed223_i_regimemat";
     $sql .= "      left  join regimematdiv  on  regimematdiv.ed219_i_codigo = serieregimemat.ed223_i_regimematdiv";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = serieregimemat.ed223_i_serie";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmaserieregimemat.ed220_i_turma";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed220_i_codigo!=null ){
         $sql2 .= " where turmaserieregimemat.ed220_i_codigo = $ed220_i_codigo "; 
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
   function sql_query_file ( $ed220_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaserieregimemat ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed220_i_codigo!=null ){
         $sql2 .= " where turmaserieregimemat.ed220_i_codigo = $ed220_i_codigo "; 
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
   function sql_query_censo ( $ed220_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaserieregimemat ";
     $sql .= "      inner join serieregimemat  on  serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat";
     $sql .= "      inner join regimemat  on  regimemat.ed218_i_codigo = serieregimemat.ed223_i_regimemat";
     $sql .= "      left  join regimematdiv  on  regimematdiv.ed219_i_codigo = serieregimemat.ed223_i_regimematdiv";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = serieregimemat.ed223_i_serie";
     $sql .= "      inner join seriecensoetapa  on  seriecensoetapa.ed133_serie = serie.ed11_i_codigo";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmaserieregimemat.ed220_i_turma";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed220_i_codigo!=null ){
         $sql2 .= " where turmaserieregimemat.ed220_i_codigo = $ed220_i_codigo "; 
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