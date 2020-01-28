<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE atividaderh
class cl_atividaderh { 
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
   var $ed01_i_codigo = 0; 
   var $ed01_c_descr = null; 
   var $ed01_c_regencia = null; 
   var $ed01_c_atualiz = null; 
   var $ed01_c_docencia = null; 
   var $ed01_c_exigeato = null; 
   var $ed01_c_efetividade = null; 
   var $ed01_i_funcaoadmin = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed01_i_codigo = int8 = Código 
                 ed01_c_descr = char(25) = Descrição 
                 ed01_c_regencia = char(1) = Regência 
                 ed01_c_atualiz = char(1) = Atualizar 
                 ed01_c_docencia = char(1) = Docência 
                 ed01_c_exigeato = char(1) = Exige Ato Legal 
                 ed01_c_efetividade = char(4) = Efetividade 
                 ed01_i_funcaoadmin = int4 = Função Administrativa 
                 ";
   //funcao construtor da classe 
   function cl_atividaderh() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atividaderh"); 
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
       $this->ed01_i_codigo = ($this->ed01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"]:$this->ed01_i_codigo);
       $this->ed01_c_descr = ($this->ed01_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_descr"]:$this->ed01_c_descr);
       $this->ed01_c_regencia = ($this->ed01_c_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_regencia"]:$this->ed01_c_regencia);
       $this->ed01_c_atualiz = ($this->ed01_c_atualiz == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_atualiz"]:$this->ed01_c_atualiz);
       $this->ed01_c_docencia = ($this->ed01_c_docencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_docencia"]:$this->ed01_c_docencia);
       $this->ed01_c_exigeato = ($this->ed01_c_exigeato == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_exigeato"]:$this->ed01_c_exigeato);
       $this->ed01_c_efetividade = ($this->ed01_c_efetividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_efetividade"]:$this->ed01_c_efetividade);
       $this->ed01_i_funcaoadmin = ($this->ed01_i_funcaoadmin == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_i_funcaoadmin"]:$this->ed01_i_funcaoadmin);
     }else{
       $this->ed01_i_codigo = ($this->ed01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"]:$this->ed01_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed01_i_codigo){ 
      $this->atualizacampos();
     if($this->ed01_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed01_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_regencia == null ){ 
       $this->erro_sql = " Campo Regência nao Informado.";
       $this->erro_campo = "ed01_c_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_atualiz == null ){ 
       $this->erro_sql = " Campo Atualizar nao Informado.";
       $this->erro_campo = "ed01_c_atualiz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_docencia == null ){ 
       $this->erro_sql = " Campo Docência nao Informado.";
       $this->erro_campo = "ed01_c_docencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_exigeato == null ){ 
       $this->erro_sql = " Campo Exige Ato Legal nao Informado.";
       $this->erro_campo = "ed01_c_exigeato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_efetividade == null ){ 
       $this->erro_sql = " Campo Efetividade nao Informado.";
       $this->erro_campo = "ed01_c_efetividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_i_funcaoadmin == null ){ 
       $this->erro_sql = " Campo Função Administrativa nao Informado.";
       $this->erro_campo = "ed01_i_funcaoadmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed01_i_codigo == "" || $ed01_i_codigo == null ){
       $result = db_query("select nextval('atividaderh_ed01_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atividaderh_ed01_i_codigo_seq do campo: ed01_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed01_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atividaderh_ed01_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed01_i_codigo)){
         $this->erro_sql = " Campo ed01_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed01_i_codigo = $ed01_i_codigo; 
       }
     }
     if(($this->ed01_i_codigo == null) || ($this->ed01_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed01_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atividaderh(
                                       ed01_i_codigo 
                                      ,ed01_c_descr 
                                      ,ed01_c_regencia 
                                      ,ed01_c_atualiz 
                                      ,ed01_c_docencia 
                                      ,ed01_c_exigeato 
                                      ,ed01_c_efetividade 
                                      ,ed01_i_funcaoadmin 
                       )
                values (
                                $this->ed01_i_codigo 
                               ,'$this->ed01_c_descr' 
                               ,'$this->ed01_c_regencia' 
                               ,'$this->ed01_c_atualiz' 
                               ,'$this->ed01_c_docencia' 
                               ,'$this->ed01_c_exigeato' 
                               ,'$this->ed01_c_efetividade' 
                               ,$this->ed01_i_funcaoadmin 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Atividades ($this->ed01_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Atividades já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Atividades ($this->ed01_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed01_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008539,'$this->ed01_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010095,1008539,'','".AddSlashes(pg_result($resaco,0,'ed01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,1008540,'','".AddSlashes(pg_result($resaco,0,'ed01_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,1008541,'','".AddSlashes(pg_result($resaco,0,'ed01_c_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,1008542,'','".AddSlashes(pg_result($resaco,0,'ed01_c_atualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,1009062,'','".AddSlashes(pg_result($resaco,0,'ed01_c_docencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,14572,'','".AddSlashes(pg_result($resaco,0,'ed01_c_exigeato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,14582,'','".AddSlashes(pg_result($resaco,0,'ed01_c_efetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010095,18535,'','".AddSlashes(pg_result($resaco,0,'ed01_i_funcaoadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed01_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update atividaderh set ";
     $virgula = "";
     if(trim($this->ed01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"])){ 
       $sql  .= $virgula." ed01_i_codigo = $this->ed01_i_codigo ";
       $virgula = ",";
       if(trim($this->ed01_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_descr"])){ 
       $sql  .= $virgula." ed01_c_descr = '$this->ed01_c_descr' ";
       $virgula = ",";
       if(trim($this->ed01_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed01_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_regencia"])){ 
       $sql  .= $virgula." ed01_c_regencia = '$this->ed01_c_regencia' ";
       $virgula = ",";
       if(trim($this->ed01_c_regencia) == null ){ 
         $this->erro_sql = " Campo Regência nao Informado.";
         $this->erro_campo = "ed01_c_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_atualiz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_atualiz"])){ 
       $sql  .= $virgula." ed01_c_atualiz = '$this->ed01_c_atualiz' ";
       $virgula = ",";
       if(trim($this->ed01_c_atualiz) == null ){ 
         $this->erro_sql = " Campo Atualizar nao Informado.";
         $this->erro_campo = "ed01_c_atualiz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_docencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_docencia"])){ 
       $sql  .= $virgula." ed01_c_docencia = '$this->ed01_c_docencia' ";
       $virgula = ",";
       if(trim($this->ed01_c_docencia) == null ){ 
         $this->erro_sql = " Campo Docência nao Informado.";
         $this->erro_campo = "ed01_c_docencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_exigeato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_exigeato"])){ 
       $sql  .= $virgula." ed01_c_exigeato = '$this->ed01_c_exigeato' ";
       $virgula = ",";
       if(trim($this->ed01_c_exigeato) == null ){ 
         $this->erro_sql = " Campo Exige Ato Legal nao Informado.";
         $this->erro_campo = "ed01_c_exigeato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_efetividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_efetividade"])){ 
       $sql  .= $virgula." ed01_c_efetividade = '$this->ed01_c_efetividade' ";
       $virgula = ",";
       if(trim($this->ed01_c_efetividade) == null ){ 
         $this->erro_sql = " Campo Efetividade nao Informado.";
         $this->erro_campo = "ed01_c_efetividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_i_funcaoadmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_funcaoadmin"])){ 
       $sql  .= $virgula." ed01_i_funcaoadmin = $this->ed01_i_funcaoadmin ";
       $virgula = ",";
       if(trim($this->ed01_i_funcaoadmin) == null ){ 
         $this->erro_sql = " Campo Função Administrativa nao Informado.";
         $this->erro_campo = "ed01_i_funcaoadmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed01_i_codigo!=null){
       $sql .= " ed01_i_codigo = $this->ed01_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed01_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008539,'$this->ed01_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"]) || $this->ed01_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010095,1008539,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_i_codigo'))."','$this->ed01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_descr"]) || $this->ed01_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,1010095,1008540,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_descr'))."','$this->ed01_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_regencia"]) || $this->ed01_c_regencia != "")
           $resac = db_query("insert into db_acount values($acount,1010095,1008541,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_regencia'))."','$this->ed01_c_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_atualiz"]) || $this->ed01_c_atualiz != "")
           $resac = db_query("insert into db_acount values($acount,1010095,1008542,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_atualiz'))."','$this->ed01_c_atualiz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_docencia"]) || $this->ed01_c_docencia != "")
           $resac = db_query("insert into db_acount values($acount,1010095,1009062,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_docencia'))."','$this->ed01_c_docencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_exigeato"]) || $this->ed01_c_exigeato != "")
           $resac = db_query("insert into db_acount values($acount,1010095,14572,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_exigeato'))."','$this->ed01_c_exigeato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_efetividade"]) || $this->ed01_c_efetividade != "")
           $resac = db_query("insert into db_acount values($acount,1010095,14582,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_efetividade'))."','$this->ed01_c_efetividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_funcaoadmin"]) || $this->ed01_i_funcaoadmin != "")
           $resac = db_query("insert into db_acount values($acount,1010095,18535,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_i_funcaoadmin'))."','$this->ed01_i_funcaoadmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Atividades nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Atividades nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed01_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed01_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008539,'$ed01_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010095,1008539,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1008540,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1008541,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1008542,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_atualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1009062,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_docencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,14572,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_exigeato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,14582,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_efetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,18535,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_i_funcaoadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atividaderh
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed01_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed01_i_codigo = $ed01_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Atividades nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Atividades nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed01_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atividaderh";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atividaderh ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed01_i_codigo!=null ){
         $sql2 .= " where atividaderh.ed01_i_codigo = $ed01_i_codigo "; 
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
   function sql_query_file ( $ed01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atividaderh ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed01_i_codigo!=null ){
         $sql2 .= " where atividaderh.ed01_i_codigo = $ed01_i_codigo "; 
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