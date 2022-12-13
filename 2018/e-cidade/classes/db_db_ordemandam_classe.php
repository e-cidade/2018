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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_ordemandam
class cl_db_ordemandam { 
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
   var $codandam = 0; 
   var $codordem = null; 
   var $dtini_dia = null; 
   var $dtini_mes = null; 
   var $dtini_ano = null; 
   var $dtini = null; 
   var $dtfim_dia = null; 
   var $dtfim_mes = null; 
   var $dtfim_ano = null; 
   var $dtfim = null; 
   var $hrini = null; 
   var $hrfim = null; 
   var $descricao = null; 
   var $id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codandam = int4 = Código do andamento 
                 codordem = varchar(15) = Código 
                 dtini = date = Data Inicial 
                 dtfim = date = Data Final 
                 hrini = char(5) = Hora de início 
                 hrfim = char(5) = Hora do término 
                 descricao = text = Descrição 
                 id_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_db_ordemandam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_ordemandam"); 
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
       $this->codandam = ($this->codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["codandam"]:$this->codandam);
       $this->codordem = ($this->codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["codordem"]:$this->codordem);
       if($this->dtini == ""){
         $this->dtini_dia = ($this->dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtini_dia"]:$this->dtini_dia);
         $this->dtini_mes = ($this->dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtini_mes"]:$this->dtini_mes);
         $this->dtini_ano = ($this->dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtini_ano"]:$this->dtini_ano);
         if($this->dtini_dia != ""){
            $this->dtini = $this->dtini_ano."-".$this->dtini_mes."-".$this->dtini_dia;
         }
       }
       if($this->dtfim == ""){
         $this->dtfim_dia = ($this->dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtfim_dia"]:$this->dtfim_dia);
         $this->dtfim_mes = ($this->dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtfim_mes"]:$this->dtfim_mes);
         $this->dtfim_ano = ($this->dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtfim_ano"]:$this->dtfim_ano);
         if($this->dtfim_dia != ""){
            $this->dtfim = $this->dtfim_ano."-".$this->dtfim_mes."-".$this->dtfim_dia;
         }
       }
       $this->hrini = ($this->hrini == ""?@$GLOBALS["HTTP_POST_VARS"]["hrini"]:$this->hrini);
       $this->hrfim = ($this->hrfim == ""?@$GLOBALS["HTTP_POST_VARS"]["hrfim"]:$this->hrfim);
       $this->descricao = ($this->descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["descricao"]:$this->descricao);
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
     }else{
       $this->codandam = ($this->codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["codandam"]:$this->codandam);
     }
   }
   // funcao para inclusao
   function incluir ($codandam){ 
      $this->atualizacampos();
     if($this->codordem == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "codordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtfim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->hrini == null ){ 
       $this->erro_sql = " Campo Hora de início nao Informado.";
       $this->erro_campo = "hrini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->hrfim == null ){ 
       $this->erro_sql = " Campo Hora do término nao Informado.";
       $this->erro_campo = "hrfim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->codandam = $codandam; 
     if(($this->codandam == null) || ($this->codandam == "") ){ 
       $this->erro_sql = " Campo codandam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_ordemandam(
                                       codandam 
                                      ,codordem 
                                      ,dtini 
                                      ,dtfim 
                                      ,hrini 
                                      ,hrfim 
                                      ,descricao 
                                      ,id_usuario 
                       )
                values (
                                $this->codandam 
                               ,'$this->codordem' 
                               ,".($this->dtini == "null" || $this->dtini == ""?"null":"'".$this->dtini."'")." 
                               ,".($this->dtfim == "null" || $this->dtfim == ""?"null":"'".$this->dtfim."'")." 
                               ,'$this->hrini' 
                               ,'$this->hrfim' 
                               ,'$this->descricao' 
                               ,$this->id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Andamento ($this->codandam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Andamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Andamento ($this->codandam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codandam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codandam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1010,'$this->codandam','I')");
       $resac = db_query("insert into db_acount values($acount,170,1010,'','".AddSlashes(pg_result($resaco,0,'codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,1003,'','".AddSlashes(pg_result($resaco,0,'codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,502,'','".AddSlashes(pg_result($resaco,0,'dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,503,'','".AddSlashes(pg_result($resaco,0,'dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,1013,'','".AddSlashes(pg_result($resaco,0,'hrini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,1014,'','".AddSlashes(pg_result($resaco,0,'hrfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,750,'','".AddSlashes(pg_result($resaco,0,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,170,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codandam=null) { 
      $this->atualizacampos();
     $sql = " update db_ordemandam set ";
     $virgula = "";
     if(trim($this->codandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codandam"])){ 
       $sql  .= $virgula." codandam = $this->codandam ";
       $virgula = ",";
       if(trim($this->codandam) == null ){ 
         $this->erro_sql = " Campo Código do andamento nao Informado.";
         $this->erro_campo = "codandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codordem"])){ 
       $sql  .= $virgula." codordem = '$this->codordem' ";
       $virgula = ",";
       if(trim($this->codordem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtini_dia"] !="") ){ 
       $sql  .= $virgula." dtini = '$this->dtini' ";
       $virgula = ",";
       if(trim($this->dtini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtini_dia"])){ 
         $sql  .= $virgula." dtini = null ";
         $virgula = ",";
         if(trim($this->dtini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtfim_dia"] !="") ){ 
       $sql  .= $virgula." dtfim = '$this->dtfim' ";
       $virgula = ",";
       if(trim($this->dtfim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtfim_dia"])){ 
         $sql  .= $virgula." dtfim = null ";
         $virgula = ",";
         if(trim($this->dtfim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->hrini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["hrini"])){ 
       $sql  .= $virgula." hrini = '$this->hrini' ";
       $virgula = ",";
       if(trim($this->hrini) == null ){ 
         $this->erro_sql = " Campo Hora de início nao Informado.";
         $this->erro_campo = "hrini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->hrfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["hrfim"])){ 
       $sql  .= $virgula." hrfim = '$this->hrfim' ";
       $virgula = ",";
       if(trim($this->hrfim) == null ){ 
         $this->erro_sql = " Campo Hora do término nao Informado.";
         $this->erro_campo = "hrfim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descricao"])){ 
       $sql  .= $virgula." descricao = '$this->descricao' ";
       $virgula = ",";
       if(trim($this->descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codandam!=null){
       $sql .= " codandam = $this->codandam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codandam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010,'$this->codandam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codandam"]))
           $resac = db_query("insert into db_acount values($acount,170,1010,'".AddSlashes(pg_result($resaco,$conresaco,'codandam'))."','$this->codandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codordem"]))
           $resac = db_query("insert into db_acount values($acount,170,1003,'".AddSlashes(pg_result($resaco,$conresaco,'codordem'))."','$this->codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtini"]))
           $resac = db_query("insert into db_acount values($acount,170,502,'".AddSlashes(pg_result($resaco,$conresaco,'dtini'))."','$this->dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtfim"]))
           $resac = db_query("insert into db_acount values($acount,170,503,'".AddSlashes(pg_result($resaco,$conresaco,'dtfim'))."','$this->dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["hrini"]))
           $resac = db_query("insert into db_acount values($acount,170,1013,'".AddSlashes(pg_result($resaco,$conresaco,'hrini'))."','$this->hrini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["hrfim"]))
           $resac = db_query("insert into db_acount values($acount,170,1014,'".AddSlashes(pg_result($resaco,$conresaco,'hrfim'))."','$this->hrfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descricao"]))
           $resac = db_query("insert into db_acount values($acount,170,750,'".AddSlashes(pg_result($resaco,$conresaco,'descricao'))."','$this->descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,170,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codandam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codandam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codandam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010,'$codandam','E')");
         $resac = db_query("insert into db_acount values($acount,170,1010,'','".AddSlashes(pg_result($resaco,$iresaco,'codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,1003,'','".AddSlashes(pg_result($resaco,$iresaco,'codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,502,'','".AddSlashes(pg_result($resaco,$iresaco,'dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,503,'','".AddSlashes(pg_result($resaco,$iresaco,'dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,1013,'','".AddSlashes(pg_result($resaco,$iresaco,'hrini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,1014,'','".AddSlashes(pg_result($resaco,$iresaco,'hrfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,750,'','".AddSlashes(pg_result($resaco,$iresaco,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,170,568,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_ordemandam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codandam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codandam = $codandam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codandam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codandam;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_ordemandam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codandam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ordemandam ";
     $sql2 = "";
     if($dbwhere==""){
       if($codandam!=null ){
         $sql2 .= " where db_ordemandam.codandam = $codandam "; 
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
   function sql_query_file ( $codandam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ordemandam ";
     $sql2 = "";
     if($dbwhere==""){
       if($codandam!=null ){
         $sql2 .= " where db_ordemandam.codandam = $codandam "; 
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