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

//MODULO: contabilidade
//CLASSE DA ENTIDADE contcearquivo
class cl_contcearquivo { 
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
   var $c11_sequencial = 0; 
   var $c11_concadtce = 0; 
   var $c11_instit = 0; 
   var $c11_dataini_dia = null; 
   var $c11_dataini_mes = null; 
   var $c11_dataini_ano = null; 
   var $c11_dataini = null; 
   var $c11_datageracao_dia = null; 
   var $c11_datageracao_mes = null; 
   var $c11_datageracao_ano = null; 
   var $c11_datageracao = null; 
   var $c11_datafim_dia = null; 
   var $c11_datafim_mes = null; 
   var $c11_datafim_ano = null; 
   var $c11_datafim = null; 
   var $c11_codigoremessa = 0; 
   var $c11_diapagtofolha = 0; 
   var $c11_infleiame = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c11_sequencial = int4 = Codigo sequencial 
                 c11_concadtce = int4 = Codigo tribunal de contas 
                 c11_instit = int4 = Cod. Instituição 
                 c11_dataini = date = Data inicial 
                 c11_datageracao = date = Data de Geração 
                 c11_datafim = date = Data final 
                 c11_codigoremessa = int4 = Codigo da remessa do lote 
                 c11_diapagtofolha = int4 = Dia de pagamento da follha 
                 c11_infleiame = text = Informações Adicionais leiame 
                 ";
   //funcao construtor da classe 
   function cl_contcearquivo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contcearquivo"); 
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
       $this->c11_sequencial = ($this->c11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_sequencial"]:$this->c11_sequencial);
       $this->c11_concadtce = ($this->c11_concadtce == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_concadtce"]:$this->c11_concadtce);
       $this->c11_instit = ($this->c11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_instit"]:$this->c11_instit);
       if($this->c11_dataini == ""){
         $this->c11_dataini_dia = ($this->c11_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_dataini_dia"]:$this->c11_dataini_dia);
         $this->c11_dataini_mes = ($this->c11_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_dataini_mes"]:$this->c11_dataini_mes);
         $this->c11_dataini_ano = ($this->c11_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_dataini_ano"]:$this->c11_dataini_ano);
         if($this->c11_dataini_dia != ""){
            $this->c11_dataini = $this->c11_dataini_ano."-".$this->c11_dataini_mes."-".$this->c11_dataini_dia;
         }
       }
       if($this->c11_datageracao == ""){
         $this->c11_datageracao_dia = ($this->c11_datageracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_datageracao_dia"]:$this->c11_datageracao_dia);
         $this->c11_datageracao_mes = ($this->c11_datageracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_datageracao_mes"]:$this->c11_datageracao_mes);
         $this->c11_datageracao_ano = ($this->c11_datageracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_datageracao_ano"]:$this->c11_datageracao_ano);
         if($this->c11_datageracao_dia != ""){
            $this->c11_datageracao = $this->c11_datageracao_ano."-".$this->c11_datageracao_mes."-".$this->c11_datageracao_dia;
         }
       }
       if($this->c11_datafim == ""){
         $this->c11_datafim_dia = ($this->c11_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_datafim_dia"]:$this->c11_datafim_dia);
         $this->c11_datafim_mes = ($this->c11_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_datafim_mes"]:$this->c11_datafim_mes);
         $this->c11_datafim_ano = ($this->c11_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_datafim_ano"]:$this->c11_datafim_ano);
         if($this->c11_datafim_dia != ""){
            $this->c11_datafim = $this->c11_datafim_ano."-".$this->c11_datafim_mes."-".$this->c11_datafim_dia;
         }
       }
       $this->c11_codigoremessa = ($this->c11_codigoremessa == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_codigoremessa"]:$this->c11_codigoremessa);
       $this->c11_diapagtofolha = ($this->c11_diapagtofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_diapagtofolha"]:$this->c11_diapagtofolha);
       $this->c11_infleiame = ($this->c11_infleiame == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_infleiame"]:$this->c11_infleiame);
     }else{
       $this->c11_sequencial = ($this->c11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c11_sequencial"]:$this->c11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c11_sequencial){ 
      $this->atualizacampos();
     if($this->c11_concadtce == null ){ 
       $this->erro_sql = " Campo Codigo tribunal de contas nao Informado.";
       $this->erro_campo = "c11_concadtce";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c11_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "c11_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c11_dataini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "c11_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c11_datageracao == null ){ 
       $this->erro_sql = " Campo Data de Geração nao Informado.";
       $this->erro_campo = "c11_datageracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c11_datafim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "c11_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c11_codigoremessa == null ){ 
       $this->erro_sql = " Campo Codigo da remessa do lote nao Informado.";
       $this->erro_campo = "c11_codigoremessa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c11_diapagtofolha == null ){ 
       $this->erro_sql = " Campo Dia de pagamento da follha nao Informado.";
       $this->erro_campo = "c11_diapagtofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c11_sequencial == "" || $c11_sequencial == null ){
       $result = db_query("select nextval('contcearquivo_c11_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contcearquivo_c11_sequencial_seq do campo: c11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from contcearquivo_c11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c11_sequencial)){
         $this->erro_sql = " Campo c11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c11_sequencial = $c11_sequencial; 
       }
     }
     if(($this->c11_sequencial == null) || ($this->c11_sequencial == "") ){ 
       $this->erro_sql = " Campo c11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contcearquivo(
                                       c11_sequencial 
                                      ,c11_concadtce 
                                      ,c11_instit 
                                      ,c11_dataini 
                                      ,c11_datageracao 
                                      ,c11_datafim 
                                      ,c11_codigoremessa 
                                      ,c11_diapagtofolha 
                                      ,c11_infleiame 
                       )
                values (
                                $this->c11_sequencial 
                               ,$this->c11_concadtce 
                               ,$this->c11_instit 
                               ,".($this->c11_dataini == "null" || $this->c11_dataini == ""?"null":"'".$this->c11_dataini."'")." 
                               ,".($this->c11_datageracao == "null" || $this->c11_datageracao == ""?"null":"'".$this->c11_datageracao."'")." 
                               ,".($this->c11_datafim == "null" || $this->c11_datafim == ""?"null":"'".$this->c11_datafim."'")." 
                               ,$this->c11_codigoremessa 
                               ,$this->c11_diapagtofolha 
                               ,'$this->c11_infleiame' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "contcearquivo ($this->c11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "contcearquivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "contcearquivo ($this->c11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11922,'$this->c11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2060,11922,'','".AddSlashes(pg_result($resaco,0,'c11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11934,'','".AddSlashes(pg_result($resaco,0,'c11_concadtce'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11924,'','".AddSlashes(pg_result($resaco,0,'c11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11925,'','".AddSlashes(pg_result($resaco,0,'c11_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11963,'','".AddSlashes(pg_result($resaco,0,'c11_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11926,'','".AddSlashes(pg_result($resaco,0,'c11_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11950,'','".AddSlashes(pg_result($resaco,0,'c11_codigoremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11949,'','".AddSlashes(pg_result($resaco,0,'c11_diapagtofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2060,11972,'','".AddSlashes(pg_result($resaco,0,'c11_infleiame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update contcearquivo set ";
     $virgula = "";
     if(trim($this->c11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_sequencial"])){ 
       $sql  .= $virgula." c11_sequencial = $this->c11_sequencial ";
       $virgula = ",";
       if(trim($this->c11_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "c11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c11_concadtce)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_concadtce"])){ 
       $sql  .= $virgula." c11_concadtce = $this->c11_concadtce ";
       $virgula = ",";
       if(trim($this->c11_concadtce) == null ){ 
         $this->erro_sql = " Campo Codigo tribunal de contas nao Informado.";
         $this->erro_campo = "c11_concadtce";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_instit"])){ 
       $sql  .= $virgula." c11_instit = $this->c11_instit ";
       $virgula = ",";
       if(trim($this->c11_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "c11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c11_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c11_dataini_dia"] !="") ){ 
       $sql  .= $virgula." c11_dataini = '$this->c11_dataini' ";
       $virgula = ",";
       if(trim($this->c11_dataini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "c11_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c11_dataini_dia"])){ 
         $sql  .= $virgula." c11_dataini = null ";
         $virgula = ",";
         if(trim($this->c11_dataini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "c11_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c11_datageracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_datageracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c11_datageracao_dia"] !="") ){ 
       $sql  .= $virgula." c11_datageracao = '$this->c11_datageracao' ";
       $virgula = ",";
       if(trim($this->c11_datageracao) == null ){ 
         $this->erro_sql = " Campo Data de Geração nao Informado.";
         $this->erro_campo = "c11_datageracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c11_datageracao_dia"])){ 
         $sql  .= $virgula." c11_datageracao = null ";
         $virgula = ",";
         if(trim($this->c11_datageracao) == null ){ 
           $this->erro_sql = " Campo Data de Geração nao Informado.";
           $this->erro_campo = "c11_datageracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c11_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c11_datafim_dia"] !="") ){ 
       $sql  .= $virgula." c11_datafim = '$this->c11_datafim' ";
       $virgula = ",";
       if(trim($this->c11_datafim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "c11_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c11_datafim_dia"])){ 
         $sql  .= $virgula." c11_datafim = null ";
         $virgula = ",";
         if(trim($this->c11_datafim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "c11_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c11_codigoremessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_codigoremessa"])){ 
       $sql  .= $virgula." c11_codigoremessa = $this->c11_codigoremessa ";
       $virgula = ",";
       if(trim($this->c11_codigoremessa) == null ){ 
         $this->erro_sql = " Campo Codigo da remessa do lote nao Informado.";
         $this->erro_campo = "c11_codigoremessa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c11_diapagtofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_diapagtofolha"])){ 
       $sql  .= $virgula." c11_diapagtofolha = $this->c11_diapagtofolha ";
       $virgula = ",";
       if(trim($this->c11_diapagtofolha) == null ){ 
         $this->erro_sql = " Campo Dia de pagamento da follha nao Informado.";
         $this->erro_campo = "c11_diapagtofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c11_infleiame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c11_infleiame"])){ 
       $sql  .= $virgula." c11_infleiame = '$this->c11_infleiame' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c11_sequencial!=null){
       $sql .= " c11_sequencial = $this->c11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11922,'$this->c11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2060,11922,'".AddSlashes(pg_result($resaco,$conresaco,'c11_sequencial'))."','$this->c11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_concadtce"]))
           $resac = db_query("insert into db_acount values($acount,2060,11934,'".AddSlashes(pg_result($resaco,$conresaco,'c11_concadtce'))."','$this->c11_concadtce',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_instit"]))
           $resac = db_query("insert into db_acount values($acount,2060,11924,'".AddSlashes(pg_result($resaco,$conresaco,'c11_instit'))."','$this->c11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_dataini"]))
           $resac = db_query("insert into db_acount values($acount,2060,11925,'".AddSlashes(pg_result($resaco,$conresaco,'c11_dataini'))."','$this->c11_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_datageracao"]))
           $resac = db_query("insert into db_acount values($acount,2060,11963,'".AddSlashes(pg_result($resaco,$conresaco,'c11_datageracao'))."','$this->c11_datageracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_datafim"]))
           $resac = db_query("insert into db_acount values($acount,2060,11926,'".AddSlashes(pg_result($resaco,$conresaco,'c11_datafim'))."','$this->c11_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_codigoremessa"]))
           $resac = db_query("insert into db_acount values($acount,2060,11950,'".AddSlashes(pg_result($resaco,$conresaco,'c11_codigoremessa'))."','$this->c11_codigoremessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_diapagtofolha"]))
           $resac = db_query("insert into db_acount values($acount,2060,11949,'".AddSlashes(pg_result($resaco,$conresaco,'c11_diapagtofolha'))."','$this->c11_diapagtofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c11_infleiame"]))
           $resac = db_query("insert into db_acount values($acount,2060,11972,'".AddSlashes(pg_result($resaco,$conresaco,'c11_infleiame'))."','$this->c11_infleiame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "contcearquivo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "contcearquivo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c11_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c11_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11922,'$c11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2060,11922,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11934,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_concadtce'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11924,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11925,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11963,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11926,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11950,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_codigoremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11949,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_diapagtofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2060,11972,'','".AddSlashes(pg_result($resaco,$iresaco,'c11_infleiame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contcearquivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c11_sequencial = $c11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "contcearquivo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "contcearquivo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:contcearquivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contcearquivo ";
     $sql .= "      inner join db_config  on  db_config.codigo = contcearquivo.c11_instit";
     $sql .= "      inner join concadtce  on  concadtce.c10_sequencial = contcearquivo.c11_concadtce";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_uf  on  db_uf.db12_codigo = concadtce.c10_db_uf";
     $sql2 = "";
     if($dbwhere==""){
       if($c11_sequencial!=null ){
         $sql2 .= " where contcearquivo.c11_sequencial = $c11_sequencial "; 
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
   function sql_query_file ( $c11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contcearquivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c11_sequencial!=null ){
         $sql2 .= " where contcearquivo.c11_sequencial = $c11_sequencial "; 
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