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

//MODULO: contabilidade
//CLASSE DA ENTIDADE inscricaopassivo
class cl_inscricaopassivo { 
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
   var $c36_sequencial = 0; 
   var $c36_cgm = 0; 
   var $c36_db_usuarios = 0; 
   var $c36_instit = 0; 
   var $c36_codele = 0; 
   var $c36_anousu = 0; 
   var $c36_conhist = 0; 
   var $c36_observacaoconhist = null; 
   var $c36_data_dia = null; 
   var $c36_data_mes = null; 
   var $c36_data_ano = null; 
   var $c36_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c36_sequencial = int4 = Inscrição Passiva 
                 c36_cgm = int4 = Favorecido 
                 c36_db_usuarios = int4 = Usuário 
                 c36_instit = int4 = Instituição 
                 c36_codele = int4 = Desdobramento 
                 c36_anousu = int4 = Ano 
                 c36_conhist = int4 = Histórico 
                 c36_observacaoconhist = text = Observação do Histórico 
                 c36_data = date = Data Inscricao 
                 ";
   //funcao construtor da classe 
   function cl_inscricaopassivo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inscricaopassivo"); 
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
       $this->c36_sequencial = ($this->c36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_sequencial"]:$this->c36_sequencial);
       $this->c36_cgm = ($this->c36_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_cgm"]:$this->c36_cgm);
       $this->c36_db_usuarios = ($this->c36_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_db_usuarios"]:$this->c36_db_usuarios);
       $this->c36_instit = ($this->c36_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_instit"]:$this->c36_instit);
       $this->c36_codele = ($this->c36_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_codele"]:$this->c36_codele);
       $this->c36_anousu = ($this->c36_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_anousu"]:$this->c36_anousu);
       $this->c36_conhist = ($this->c36_conhist == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_conhist"]:$this->c36_conhist);
       $this->c36_observacaoconhist = ($this->c36_observacaoconhist == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_observacaoconhist"]:$this->c36_observacaoconhist);
       if($this->c36_data == ""){
         $this->c36_data_dia = ($this->c36_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_data_dia"]:$this->c36_data_dia);
         $this->c36_data_mes = ($this->c36_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_data_mes"]:$this->c36_data_mes);
         $this->c36_data_ano = ($this->c36_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_data_ano"]:$this->c36_data_ano);
         if($this->c36_data_dia != ""){
            $this->c36_data = $this->c36_data_ano."-".$this->c36_data_mes."-".$this->c36_data_dia;
         }
       }
     }else{
       $this->c36_sequencial = ($this->c36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c36_sequencial"]:$this->c36_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c36_sequencial){ 
      $this->atualizacampos();
     if($this->c36_cgm == null ){ 
       $this->erro_sql = " Campo Favorecido nao Informado.";
       $this->erro_campo = "c36_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_db_usuarios == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "c36_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "c36_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_codele == null ){ 
       $this->erro_sql = " Campo Desdobramento nao Informado.";
       $this->erro_campo = "c36_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c36_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_conhist == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "c36_conhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_observacaoconhist == null ){ 
       $this->erro_sql = " Campo Observação do Histórico nao Informado.";
       $this->erro_campo = "c36_observacaoconhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c36_data == null ){ 
       $this->erro_sql = " Campo Data Inscricao nao Informado.";
       $this->erro_campo = "c36_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c36_sequencial == "" || $c36_sequencial == null ){
       $result = db_query("select nextval('inscricaopassivo_c36_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inscricaopassivo_c36_sequencial_seq do campo: c36_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c36_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from inscricaopassivo_c36_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c36_sequencial)){
         $this->erro_sql = " Campo c36_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c36_sequencial = $c36_sequencial; 
       }
     }
     if(($this->c36_sequencial == null) || ($this->c36_sequencial == "") ){ 
       $this->erro_sql = " Campo c36_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inscricaopassivo(
                                       c36_sequencial 
                                      ,c36_cgm 
                                      ,c36_db_usuarios 
                                      ,c36_instit 
                                      ,c36_codele 
                                      ,c36_anousu 
                                      ,c36_conhist 
                                      ,c36_observacaoconhist 
                                      ,c36_data 
                       )
                values (
                                $this->c36_sequencial 
                               ,$this->c36_cgm 
                               ,$this->c36_db_usuarios 
                               ,$this->c36_instit 
                               ,$this->c36_codele 
                               ,$this->c36_anousu 
                               ,$this->c36_conhist 
                               ,'$this->c36_observacaoconhist' 
                               ,".($this->c36_data == "null" || $this->c36_data == ""?"null":"'".$this->c36_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inscrição do Passivos sem Suporte Orçamentário ($this->c36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inscrição do Passivos sem Suporte Orçamentário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inscrição do Passivos sem Suporte Orçamentário ($this->c36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c36_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c36_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18992,'$this->c36_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3378,18992,'','".AddSlashes(pg_result($resaco,0,'c36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,18993,'','".AddSlashes(pg_result($resaco,0,'c36_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,18994,'','".AddSlashes(pg_result($resaco,0,'c36_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,19012,'','".AddSlashes(pg_result($resaco,0,'c36_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,19044,'','".AddSlashes(pg_result($resaco,0,'c36_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,19045,'','".AddSlashes(pg_result($resaco,0,'c36_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,19049,'','".AddSlashes(pg_result($resaco,0,'c36_conhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,19050,'','".AddSlashes(pg_result($resaco,0,'c36_observacaoconhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3378,19010,'','".AddSlashes(pg_result($resaco,0,'c36_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c36_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update inscricaopassivo set ";
     $virgula = "";
     if(trim($this->c36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_sequencial"])){ 
       $sql  .= $virgula." c36_sequencial = $this->c36_sequencial ";
       $virgula = ",";
       if(trim($this->c36_sequencial) == null ){ 
         $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
         $this->erro_campo = "c36_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_cgm"])){ 
       $sql  .= $virgula." c36_cgm = $this->c36_cgm ";
       $virgula = ",";
       if(trim($this->c36_cgm) == null ){ 
         $this->erro_sql = " Campo Favorecido nao Informado.";
         $this->erro_campo = "c36_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_db_usuarios"])){ 
       $sql  .= $virgula." c36_db_usuarios = $this->c36_db_usuarios ";
       $virgula = ",";
       if(trim($this->c36_db_usuarios) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "c36_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_instit"])){ 
       $sql  .= $virgula." c36_instit = $this->c36_instit ";
       $virgula = ",";
       if(trim($this->c36_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c36_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_codele"])){ 
       $sql  .= $virgula." c36_codele = $this->c36_codele ";
       $virgula = ",";
       if(trim($this->c36_codele) == null ){ 
         $this->erro_sql = " Campo Desdobramento nao Informado.";
         $this->erro_campo = "c36_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_anousu"])){ 
       $sql  .= $virgula." c36_anousu = $this->c36_anousu ";
       $virgula = ",";
       if(trim($this->c36_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c36_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_conhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_conhist"])){ 
       $sql  .= $virgula." c36_conhist = $this->c36_conhist ";
       $virgula = ",";
       if(trim($this->c36_conhist) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "c36_conhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_observacaoconhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_observacaoconhist"])){ 
       $sql  .= $virgula." c36_observacaoconhist = '$this->c36_observacaoconhist' ";
       $virgula = ",";
       if(trim($this->c36_observacaoconhist) == null ){ 
         $this->erro_sql = " Campo Observação do Histórico nao Informado.";
         $this->erro_campo = "c36_observacaoconhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c36_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c36_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c36_data_dia"] !="") ){ 
       $sql  .= $virgula." c36_data = '$this->c36_data' ";
       $virgula = ",";
       if(trim($this->c36_data) == null ){ 
         $this->erro_sql = " Campo Data Inscricao nao Informado.";
         $this->erro_campo = "c36_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c36_data_dia"])){ 
         $sql  .= $virgula." c36_data = null ";
         $virgula = ",";
         if(trim($this->c36_data) == null ){ 
           $this->erro_sql = " Campo Data Inscricao nao Informado.";
           $this->erro_campo = "c36_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c36_sequencial!=null){
       $sql .= " c36_sequencial = $this->c36_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c36_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18992,'$this->c36_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_sequencial"]) || $this->c36_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3378,18992,'".AddSlashes(pg_result($resaco,$conresaco,'c36_sequencial'))."','$this->c36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_cgm"]) || $this->c36_cgm != "")
           $resac = db_query("insert into db_acount values($acount,3378,18993,'".AddSlashes(pg_result($resaco,$conresaco,'c36_cgm'))."','$this->c36_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_db_usuarios"]) || $this->c36_db_usuarios != "")
           $resac = db_query("insert into db_acount values($acount,3378,18994,'".AddSlashes(pg_result($resaco,$conresaco,'c36_db_usuarios'))."','$this->c36_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_instit"]) || $this->c36_instit != "")
           $resac = db_query("insert into db_acount values($acount,3378,19012,'".AddSlashes(pg_result($resaco,$conresaco,'c36_instit'))."','$this->c36_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_codele"]) || $this->c36_codele != "")
           $resac = db_query("insert into db_acount values($acount,3378,19044,'".AddSlashes(pg_result($resaco,$conresaco,'c36_codele'))."','$this->c36_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_anousu"]) || $this->c36_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3378,19045,'".AddSlashes(pg_result($resaco,$conresaco,'c36_anousu'))."','$this->c36_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_conhist"]) || $this->c36_conhist != "")
           $resac = db_query("insert into db_acount values($acount,3378,19049,'".AddSlashes(pg_result($resaco,$conresaco,'c36_conhist'))."','$this->c36_conhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_observacaoconhist"]) || $this->c36_observacaoconhist != "")
           $resac = db_query("insert into db_acount values($acount,3378,19050,'".AddSlashes(pg_result($resaco,$conresaco,'c36_observacaoconhist'))."','$this->c36_observacaoconhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c36_data"]) || $this->c36_data != "")
           $resac = db_query("insert into db_acount values($acount,3378,19010,'".AddSlashes(pg_result($resaco,$conresaco,'c36_data'))."','$this->c36_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inscrição do Passivos sem Suporte Orçamentário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inscrição do Passivos sem Suporte Orçamentário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c36_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c36_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18992,'$c36_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3378,18992,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,18993,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,18994,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,19012,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,19044,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,19045,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,19049,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_conhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,19050,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_observacaoconhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3378,19010,'','".AddSlashes(pg_result($resaco,$iresaco,'c36_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inscricaopassivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c36_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c36_sequencial = $c36_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inscrição do Passivos sem Suporte Orçamentário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inscrição do Passivos sem Suporte Orçamentário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c36_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inscricaopassivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inscricaopassivo ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = inscricaopassivo.c36_cgm";
     $sql .= "      inner join db_config  on  db_config.codigo = inscricaopassivo.c36_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaopassivo.c36_db_usuarios";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = inscricaopassivo.c36_codele and  orcelemento.o56_anousu = inscricaopassivo.c36_anousu";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = inscricaopassivo.c36_conhist";
     $sql .= "      inner join cgm as a on  a.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($c36_sequencial!=null ){
         $sql2 .= " where inscricaopassivo.c36_sequencial = $c36_sequencial "; 
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
   function sql_query_file ( $c36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inscricaopassivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c36_sequencial!=null ){
         $sql2 .= " where inscricaopassivo.c36_sequencial = $c36_sequencial "; 
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
   /**
   * Utilizar este método ao invéz do sql_query
   * @param integer $c36_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_novo ($c36_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from inscricaopassivo ";
    $sql .= "      inner join cgm                on cgm.z01_numcgm             = inscricaopassivo.c36_cgm";
    $sql .= "      inner join db_config          on db_config.codigo           = inscricaopassivo.c36_instit";
    $sql .= "      inner join db_usuarios        on db_usuarios.id_usuario     = inscricaopassivo.c36_db_usuarios";
    $sql .= "      inner join orcelemento        on orcelemento.o56_codele     = inscricaopassivo.c36_codele  ";
    $sql .= "                                   and orcelemento.o56_anousu     = inscricaopassivo.c36_anousu";
    $sql .= "      inner join cgm as instituicao on instituicao.z01_numcgm     = db_config.numcgm";
    $sql .= "      inner join db_tipoinstit      on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql .= "      inner join conhist            on  conhist.c50_codhist       = inscricaopassivo.c36_conhist";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($c36_sequencial != null) {
        $sql2 .= " where inscricaopassivo.c36_sequencial = $c36_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
   function sql_query_informacoes_inscricao($c36_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if($campos != "*" ) {
      
      $campos_sql  = split("#",$campos);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from inscricaopassivo ";
    $sql .= " inner join cgm 					 on cgm.z01_numcgm             = inscricaopassivo.c36_cgm";
    $sql .= " inner join db_usuarios   on db_usuarios.id_usuario     = inscricaopassivo.c36_db_usuarios";
    $sql .= " inner join db_config     on db_config.codigo           = inscricaopassivo.c36_instit";
    $sql .= " inner join orcelemento   on orcelemento.o56_codele     = inscricaopassivo.c36_codele "; 
    $sql .= "                         and orcelemento.o56_anousu     = inscricaopassivo.c36_anousu";
    $sql .= " inner join db_tipoinstit on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql .= " inner join conhist       on  conhist.c50_codhist       = inscricaopassivo.c36_conhist";
    $sql .= " left join inscricaopassivaanulada on c39_inscricaopassivo = c36_sequencial";
    $sql .= " left join empautorizainscricaopassivo on e16_inscricaopassivo = c36_sequencial";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($c36_sequencial != null) {
        $sql2 .= " where inscricaopassivo.c36_sequencial = $c36_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  /**
   * Método para buscar os dados da conta crédito do lancamento da inscricao passaiva
   */
  function sql_lancamento_inscricao ($c36_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
    $sql = "select ";
    if ($campos != "*" ) {
  
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
  
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from inscricaopassivo ";
    $sql .= "      inner join cgm                          on   cgm.z01_numcgm       = inscricaopassivo.c36_cgm";
    $sql .= "      inner join db_config                    on   db_config.codigo     = inscricaopassivo.c36_instit";
    $sql .= "      inner join conlancaminscricaopassivo    on   c37_inscricaopassivo = inscricaopassivo.c36_sequencial";
    $sql .= "      inner join conlancam                    on   c70_codlan           = conlancaminscricaopassivo.c37_conlancam";
    $sql .= "                                              and  c70_anousu           = ".db_getsession("DB_anousu");
    $sql .= "      inner join conlancamval                 on   c69_codlan           = conlancam.c70_codlan";
    $sql .= "                                              and  c69_anousu           = ".db_getsession("DB_anousu");
    $sql .= "      inner join conplanoreduz                on   c61_reduz            = conlancamval.c69_credito";
    $sql .= "                                              and  c61_anousu           =".db_getsession("DB_anousu");
    $sql .= "      inner join conplano                     on   c60_codcon           = conplanoreduz.c61_codcon";
    $sql .= "                                              and  c60_anousu           =".db_getsession("DB_anousu");
    $sql2 = "";
    
    if ($dbwhere == "") {
  
      if ($c36_sequencial != null) {
        $sql2 .= " where inscricaopassivo.c36_sequencial = $c36_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
  
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
  
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
  
  function sql_query_inscricao_baixapagamento($c36_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
    $sql = "select ";
    if($campos != "*" ) {
  
      $campos_sql  = split("#",$campos);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
  
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from inscricaopassivo ";
    $sql .= " inner join cgm 					 on cgm.z01_numcgm             = inscricaopassivo.c36_cgm";
    $sql .= " inner join db_usuarios   on db_usuarios.id_usuario     = inscricaopassivo.c36_db_usuarios";
    $sql .= " inner join db_config     on db_config.codigo           = inscricaopassivo.c36_instit";
    $sql .= " inner join orcelemento   on orcelemento.o56_codele     = inscricaopassivo.c36_codele ";
    $sql .= "                         and orcelemento.o56_anousu     = inscricaopassivo.c36_anousu";
    $sql .= " inner join db_tipoinstit on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql .= " inner join conhist       on  conhist.c50_codhist       = inscricaopassivo.c36_conhist";
    $sql .= " left join inscricaopassivaanulada on c39_inscricaopassivo = c36_sequencial";
    $sql .= " left join empautorizainscricaopassivo on e16_inscricaopassivo = c36_sequencial";
    $sql .= " left join inscricaopassivoslip    on  c36_sequencial = c109_inscricaopassiva";
    
    $sql2 = "";
    if ($dbwhere == "") {
  
      if ($c36_sequencial != null) {
        $sql2 .= " where inscricaopassivo.c36_sequencial = $c36_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
  
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
  
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
}
?>