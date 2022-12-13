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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE tipoasse
class cl_tipoasse { 
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
   var $h12_codigo = 0; 
   var $h12_assent = null; 
   var $h12_descr = null; 
   var $h12_dias = 0; 
   var $h12_relvan = 'f'; 
   var $h12_relass = 'f'; 
   var $h12_reltot = 0; 
   var $h12_relgra = 'f'; 
   var $h12_tipo = null; 
   var $h12_graefe = 'f'; 
   var $h12_efetiv = null; 
   var $h12_tipefe = null; 
   var $h12_regenc = 'f'; 
   var $h12_vinculaperiodoaquisitivo = 'f'; 
   var $h12_tiporeajuste = 0; 
   var $h12_natureza = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h12_codigo = int4 = Sequencial assentamento 
                 h12_assent = varchar(5) = Código 
                 h12_descr = varchar(40) = Descrição 
                 h12_dias = int4 = Dias 
                 h12_relvan = bool = Relaciona com Vantagem 
                 h12_relass = bool = Relaciona como Assentamento 
                 h12_reltot = int4 = Totais 
                 h12_relgra = bool = Grade de FG 
                 h12_tipo = varchar(1) = Tipo 
                 h12_graefe = bool = Grade efetividade 
                 h12_efetiv = varchar(1) = Efetividade 
                 h12_tipefe = varchar(1) = Tipo de efetividade 
                 h12_regenc = bool = Regência 
                 h12_vinculaperiodoaquisitivo = bool = Vincular Período Aquisitivo de Férias 
                 h12_tiporeajuste = int4 = Tipo de Reajuste Salarial 
                 h12_natureza = int4 = Natureza 
                 ";
   //funcao construtor da classe 
   function cl_tipoasse() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoasse"); 
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
       $this->h12_codigo = ($this->h12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_codigo"]:$this->h12_codigo);
       $this->h12_assent = ($this->h12_assent == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_assent"]:$this->h12_assent);
       $this->h12_descr = ($this->h12_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_descr"]:$this->h12_descr);
       $this->h12_dias = ($this->h12_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_dias"]:$this->h12_dias);
       $this->h12_relvan = ($this->h12_relvan == "f"?@$GLOBALS["HTTP_POST_VARS"]["h12_relvan"]:$this->h12_relvan);
       $this->h12_relass = ($this->h12_relass == "f"?@$GLOBALS["HTTP_POST_VARS"]["h12_relass"]:$this->h12_relass);
       $this->h12_reltot = ($this->h12_reltot == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_reltot"]:$this->h12_reltot);
       $this->h12_relgra = ($this->h12_relgra == "f"?@$GLOBALS["HTTP_POST_VARS"]["h12_relgra"]:$this->h12_relgra);
       $this->h12_tipo = ($this->h12_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_tipo"]:$this->h12_tipo);
       $this->h12_graefe = ($this->h12_graefe == "f"?@$GLOBALS["HTTP_POST_VARS"]["h12_graefe"]:$this->h12_graefe);
       $this->h12_efetiv = ($this->h12_efetiv == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_efetiv"]:$this->h12_efetiv);
       $this->h12_tipefe = ($this->h12_tipefe == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_tipefe"]:$this->h12_tipefe);
       $this->h12_regenc = ($this->h12_regenc == "f"?@$GLOBALS["HTTP_POST_VARS"]["h12_regenc"]:$this->h12_regenc);
       $this->h12_vinculaperiodoaquisitivo = ($this->h12_vinculaperiodoaquisitivo == "f"?@$GLOBALS["HTTP_POST_VARS"]["h12_vinculaperiodoaquisitivo"]:$this->h12_vinculaperiodoaquisitivo);
       $this->h12_tiporeajuste = ($this->h12_tiporeajuste == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_tiporeajuste"]:$this->h12_tiporeajuste);
       $this->h12_natureza = ($this->h12_natureza == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_natureza"]:$this->h12_natureza);
     }else{
       $this->h12_codigo = ($this->h12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h12_codigo"]:$this->h12_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($h12_codigo){ 
      $this->atualizacampos();
     if($this->h12_assent == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "h12_assent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_descr == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "h12_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_dias == null ){ 
       $this->erro_sql = " Campo Dias não informado.";
       $this->erro_campo = "h12_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_relvan == null ){ 
       $this->erro_sql = " Campo Relaciona com Vantagem não informado.";
       $this->erro_campo = "h12_relvan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_relass == null ){ 
       $this->erro_sql = " Campo Relaciona como Assentamento não informado.";
       $this->erro_campo = "h12_relass";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_reltot == null ){ 
       $this->erro_sql = " Campo Totais não informado.";
       $this->erro_campo = "h12_reltot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_relgra == null ){ 
       $this->erro_sql = " Campo Grade de FG não informado.";
       $this->erro_campo = "h12_relgra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_tipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "h12_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_graefe == null ){ 
       $this->erro_sql = " Campo Grade efetividade não informado.";
       $this->erro_campo = "h12_graefe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_efetiv == null ){ 
       $this->erro_sql = " Campo Efetividade não informado.";
       $this->erro_campo = "h12_efetiv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_tipefe == null ){ 
       $this->erro_sql = " Campo Tipo de efetividade não informado.";
       $this->erro_campo = "h12_tipefe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_regenc == null ){ 
       $this->erro_sql = " Campo Regência não informado.";
       $this->erro_campo = "h12_regenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h12_vinculaperiodoaquisitivo == null ){ 
       $this->h12_vinculaperiodoaquisitivo = "f";
     }
     if($this->h12_tiporeajuste == null ){ 
       $this->h12_tiporeajuste = "0";
     }
     if($this->h12_natureza == null ){ 
       $this->erro_sql = " Campo Natureza não informado.";
       $this->erro_campo = "h12_natureza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h12_codigo == "" || $h12_codigo == null ){
       $result = db_query("select nextval('tipoasse_h12_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoasse_h12_codigo_seq do campo: h12_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h12_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoasse_h12_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $h12_codigo)){
         $this->erro_sql = " Campo h12_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h12_codigo = $h12_codigo; 
       }
     }
     if(($this->h12_codigo == null) || ($this->h12_codigo == "") ){ 
       $this->erro_sql = " Campo h12_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoasse(
                                       h12_codigo 
                                      ,h12_assent 
                                      ,h12_descr 
                                      ,h12_dias 
                                      ,h12_relvan 
                                      ,h12_relass 
                                      ,h12_reltot 
                                      ,h12_relgra 
                                      ,h12_tipo 
                                      ,h12_graefe 
                                      ,h12_efetiv 
                                      ,h12_tipefe 
                                      ,h12_regenc 
                                      ,h12_vinculaperiodoaquisitivo 
                                      ,h12_tiporeajuste 
                                      ,h12_natureza 
                       )
                values (
                                $this->h12_codigo 
                               ,'$this->h12_assent' 
                               ,'$this->h12_descr' 
                               ,$this->h12_dias 
                               ,'$this->h12_relvan' 
                               ,'$this->h12_relass' 
                               ,$this->h12_reltot 
                               ,'$this->h12_relgra' 
                               ,'$this->h12_tipo' 
                               ,'$this->h12_graefe' 
                               ,'$this->h12_efetiv' 
                               ,'$this->h12_tipefe' 
                               ,'$this->h12_regenc' 
                               ,'$this->h12_vinculaperiodoaquisitivo' 
                               ,$this->h12_tiporeajuste 
                               ,$this->h12_natureza 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos Tipos de Assentamento                 ($this->h12_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos Tipos de Assentamento                 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos Tipos de Assentamento                 ($this->h12_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h12_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h12_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9508,'$this->h12_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,596,9508,'','".AddSlashes(pg_result($resaco,0,'h12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4498,'','".AddSlashes(pg_result($resaco,0,'h12_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4499,'','".AddSlashes(pg_result($resaco,0,'h12_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4500,'','".AddSlashes(pg_result($resaco,0,'h12_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4501,'','".AddSlashes(pg_result($resaco,0,'h12_relvan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4502,'','".AddSlashes(pg_result($resaco,0,'h12_relass'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4503,'','".AddSlashes(pg_result($resaco,0,'h12_reltot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4504,'','".AddSlashes(pg_result($resaco,0,'h12_relgra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4505,'','".AddSlashes(pg_result($resaco,0,'h12_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4506,'','".AddSlashes(pg_result($resaco,0,'h12_graefe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4507,'','".AddSlashes(pg_result($resaco,0,'h12_efetiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4508,'','".AddSlashes(pg_result($resaco,0,'h12_tipefe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,4509,'','".AddSlashes(pg_result($resaco,0,'h12_regenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,20385,'','".AddSlashes(pg_result($resaco,0,'h12_vinculaperiodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,20391,'','".AddSlashes(pg_result($resaco,0,'h12_tiporeajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,596,21167,'','".AddSlashes(pg_result($resaco,0,'h12_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h12_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tipoasse set ";
     $virgula = "";
     if(trim($this->h12_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_codigo"])){ 
       $sql  .= $virgula." h12_codigo = $this->h12_codigo ";
       $virgula = ",";
       if(trim($this->h12_codigo) == null ){ 
         $this->erro_sql = " Campo Sequencial assentamento não informado.";
         $this->erro_campo = "h12_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_assent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_assent"])){ 
       $sql  .= $virgula." h12_assent = '$this->h12_assent' ";
       $virgula = ",";
       if(trim($this->h12_assent) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "h12_assent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_descr"])){ 
       $sql  .= $virgula." h12_descr = '$this->h12_descr' ";
       $virgula = ",";
       if(trim($this->h12_descr) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "h12_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_dias"])){ 
       $sql  .= $virgula." h12_dias = $this->h12_dias ";
       $virgula = ",";
       if(trim($this->h12_dias) == null ){ 
         $this->erro_sql = " Campo Dias não informado.";
         $this->erro_campo = "h12_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_relvan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_relvan"])){ 
       $sql  .= $virgula." h12_relvan = '$this->h12_relvan' ";
       $virgula = ",";
       if(trim($this->h12_relvan) == null ){ 
         $this->erro_sql = " Campo Relaciona com Vantagem não informado.";
         $this->erro_campo = "h12_relvan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_relass)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_relass"])){ 
       $sql  .= $virgula." h12_relass = '$this->h12_relass' ";
       $virgula = ",";
       if(trim($this->h12_relass) == null ){ 
         $this->erro_sql = " Campo Relaciona como Assentamento não informado.";
         $this->erro_campo = "h12_relass";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_reltot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_reltot"])){ 
       $sql  .= $virgula." h12_reltot = $this->h12_reltot ";
       $virgula = ",";
       if(trim($this->h12_reltot) == null ){ 
         $this->erro_sql = " Campo Totais não informado.";
         $this->erro_campo = "h12_reltot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_relgra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_relgra"])){ 
       $sql  .= $virgula." h12_relgra = '$this->h12_relgra' ";
       $virgula = ",";
       if(trim($this->h12_relgra) == null ){ 
         $this->erro_sql = " Campo Grade de FG não informado.";
         $this->erro_campo = "h12_relgra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_tipo"])){ 
       $sql  .= $virgula." h12_tipo = '$this->h12_tipo' ";
       $virgula = ",";
       if(trim($this->h12_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "h12_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_graefe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_graefe"])){ 
       $sql  .= $virgula." h12_graefe = '$this->h12_graefe' ";
       $virgula = ",";
       if(trim($this->h12_graefe) == null ){ 
         $this->erro_sql = " Campo Grade efetividade não informado.";
         $this->erro_campo = "h12_graefe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_efetiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_efetiv"])){ 
       $sql  .= $virgula." h12_efetiv = '$this->h12_efetiv' ";
       $virgula = ",";
       if(trim($this->h12_efetiv) == null ){ 
         $this->erro_sql = " Campo Efetividade não informado.";
         $this->erro_campo = "h12_efetiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_tipefe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_tipefe"])){ 
       $sql  .= $virgula." h12_tipefe = '$this->h12_tipefe' ";
       $virgula = ",";
       if(trim($this->h12_tipefe) == null ){ 
         $this->erro_sql = " Campo Tipo de efetividade não informado.";
         $this->erro_campo = "h12_tipefe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_regenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_regenc"])){ 
       $sql  .= $virgula." h12_regenc = '$this->h12_regenc' ";
       $virgula = ",";
       if(trim($this->h12_regenc) == null ){ 
         $this->erro_sql = " Campo Regência não informado.";
         $this->erro_campo = "h12_regenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h12_vinculaperiodoaquisitivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_vinculaperiodoaquisitivo"])){ 
       $sql  .= $virgula." h12_vinculaperiodoaquisitivo = '$this->h12_vinculaperiodoaquisitivo' ";
       $virgula = ",";
     }
     if(trim($this->h12_tiporeajuste)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_tiporeajuste"])){ 
        if(trim($this->h12_tiporeajuste)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h12_tiporeajuste"])){ 
           $this->h12_tiporeajuste = "0" ; 
        } 
       $sql  .= $virgula." h12_tiporeajuste = $this->h12_tiporeajuste ";
       $virgula = ",";
     }
     if(trim($this->h12_natureza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h12_natureza"])){ 
       $sql  .= $virgula." h12_natureza = $this->h12_natureza ";
       $virgula = ",";
       if(trim($this->h12_natureza) == null ){ 
         $this->erro_sql = " Campo Natureza não informado.";
         $this->erro_campo = "h12_natureza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h12_codigo!=null){
       $sql .= " h12_codigo = $this->h12_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h12_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9508,'$this->h12_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_codigo"]) || $this->h12_codigo != "")
             $resac = db_query("insert into db_acount values($acount,596,9508,'".AddSlashes(pg_result($resaco,$conresaco,'h12_codigo'))."','$this->h12_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_assent"]) || $this->h12_assent != "")
             $resac = db_query("insert into db_acount values($acount,596,4498,'".AddSlashes(pg_result($resaco,$conresaco,'h12_assent'))."','$this->h12_assent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_descr"]) || $this->h12_descr != "")
             $resac = db_query("insert into db_acount values($acount,596,4499,'".AddSlashes(pg_result($resaco,$conresaco,'h12_descr'))."','$this->h12_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_dias"]) || $this->h12_dias != "")
             $resac = db_query("insert into db_acount values($acount,596,4500,'".AddSlashes(pg_result($resaco,$conresaco,'h12_dias'))."','$this->h12_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_relvan"]) || $this->h12_relvan != "")
             $resac = db_query("insert into db_acount values($acount,596,4501,'".AddSlashes(pg_result($resaco,$conresaco,'h12_relvan'))."','$this->h12_relvan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_relass"]) || $this->h12_relass != "")
             $resac = db_query("insert into db_acount values($acount,596,4502,'".AddSlashes(pg_result($resaco,$conresaco,'h12_relass'))."','$this->h12_relass',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_reltot"]) || $this->h12_reltot != "")
             $resac = db_query("insert into db_acount values($acount,596,4503,'".AddSlashes(pg_result($resaco,$conresaco,'h12_reltot'))."','$this->h12_reltot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_relgra"]) || $this->h12_relgra != "")
             $resac = db_query("insert into db_acount values($acount,596,4504,'".AddSlashes(pg_result($resaco,$conresaco,'h12_relgra'))."','$this->h12_relgra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_tipo"]) || $this->h12_tipo != "")
             $resac = db_query("insert into db_acount values($acount,596,4505,'".AddSlashes(pg_result($resaco,$conresaco,'h12_tipo'))."','$this->h12_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_graefe"]) || $this->h12_graefe != "")
             $resac = db_query("insert into db_acount values($acount,596,4506,'".AddSlashes(pg_result($resaco,$conresaco,'h12_graefe'))."','$this->h12_graefe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_efetiv"]) || $this->h12_efetiv != "")
             $resac = db_query("insert into db_acount values($acount,596,4507,'".AddSlashes(pg_result($resaco,$conresaco,'h12_efetiv'))."','$this->h12_efetiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_tipefe"]) || $this->h12_tipefe != "")
             $resac = db_query("insert into db_acount values($acount,596,4508,'".AddSlashes(pg_result($resaco,$conresaco,'h12_tipefe'))."','$this->h12_tipefe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_regenc"]) || $this->h12_regenc != "")
             $resac = db_query("insert into db_acount values($acount,596,4509,'".AddSlashes(pg_result($resaco,$conresaco,'h12_regenc'))."','$this->h12_regenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_vinculaperiodoaquisitivo"]) || $this->h12_vinculaperiodoaquisitivo != "")
             $resac = db_query("insert into db_acount values($acount,596,20385,'".AddSlashes(pg_result($resaco,$conresaco,'h12_vinculaperiodoaquisitivo'))."','$this->h12_vinculaperiodoaquisitivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_tiporeajuste"]) || $this->h12_tiporeajuste != "")
             $resac = db_query("insert into db_acount values($acount,596,20391,'".AddSlashes(pg_result($resaco,$conresaco,'h12_tiporeajuste'))."','$this->h12_tiporeajuste',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h12_natureza"]) || $this->h12_natureza != "")
             $resac = db_query("insert into db_acount values($acount,596,21167,'".AddSlashes(pg_result($resaco,$conresaco,'h12_natureza'))."','$this->h12_natureza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Tipos de Assentamento                 não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h12_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Tipos de Assentamento                 não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h12_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($h12_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9508,'$h12_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,596,9508,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4498,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4499,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4500,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4501,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_relvan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4502,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_relass'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4503,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_reltot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4504,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_relgra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4505,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4506,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_graefe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4507,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_efetiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4508,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_tipefe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,4509,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_regenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,20385,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_vinculaperiodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,20391,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_tiporeajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,596,21167,'','".AddSlashes(pg_result($resaco,$iresaco,'h12_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tipoasse
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h12_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h12_codigo = $h12_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_msg    = "Não é possível excluir Tipo de Assentamento já vinculado a Assentamento.\\n";
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Tipos de Assentamento                 não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
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
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tipoasse";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h12_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= "  from tipoasse ";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql2 = "";
     if($dbwhere==""){
       if($h12_codigo!=null ){
         $sql2 .= " where tipoasse.h12_codigo = $h12_codigo "; 
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
   function sql_query_file ( $h12_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= "  from tipoasse ";
     $sql2 = "";
     if($dbwhere==""){
       if($h12_codigo!=null ){
         $sql2 .= " where tipoasse.h12_codigo = $h12_codigo "; 
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

  function sql_tipoAssentamento($iCodigoAssentamento){

    $sSql  = "select case                                                              ";
    $sSql .= "         when h40_lancahaver = 1 then true                               ";
    $sSql .= "         when h40_lancahaver = 2 then false                              ";
    $sSql .= "         else null                                                       ";
    $sSql .= "       end as  lsomadiminui                                              ";
    $sSql .= "  from tipoasse                                                          ";
    $sSql .= "       inner join portariatipo    on h30_tipoasse       = h12_codigo     ";
    $sSql .= "       inner join portariaproced  on h30_portariaproced = h40_sequencial ";
    $sSql .= " where h12_codigo = {$iCodigoAssentamento}                               ";
    $sSql .= "   and h12_vinculaperiodoaquisitivo is true;                             ";

    return $sSql;
  }
}
?>