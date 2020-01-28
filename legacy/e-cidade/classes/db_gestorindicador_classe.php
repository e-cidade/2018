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

//MODULO: gestorbi
//CLASSE DA ENTIDADE gestorindicador
class cl_gestorindicador { 
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
   var $g04_sequencial = 0; 
   var $g04_periodicidade = 0; 
   var $g04_descricao = null; 
   var $g04_definicao = null; 
   var $g04_tipo = 0; 
   var $g04_faixainicial = 0; 
   var $g04_faixafinal = 0; 
   var $g04_faixaverdeinicial = 0; 
   var $g04_faixaverdefinal = 0; 
   var $g04_faixaamarelainicial = 0; 
   var $g04_faixaamarelafinal = 0; 
   var $g04_faixavermelhainicial = 0; 
   var $g04_faixavermelhafinal = 0; 
   var $g04_emitealerta = 'f'; 
   var $g04_valoralerta = 0; 
   var $g04_emailalerta = null; 
   var $g04_mensagemalerta = null; 
   var $g04_limite_dia = null; 
   var $g04_limite_mes = null; 
   var $g04_limite_ano = null; 
   var $g04_limite = null; 
   var $g04_link = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 g04_sequencial = int4 = Código Sequencial 
                 g04_periodicidade = int4 = Periodicidade do Indicador 
                 g04_descricao = varchar(100) = Descrição 
                 g04_definicao = varchar(100) = Definição do Indicador 
                 g04_tipo = int4 = Tipo do Indicador 
                 g04_faixainicial = float8 = Faixa Inicial do Indicador 
                 g04_faixafinal = float8 = Faixa Final do Indicador 
                 g04_faixaverdeinicial = float8 = Valor Inicial da Faixa Verde 
                 g04_faixaverdefinal = float8 = Valor Final da Faixa Verde 
                 g04_faixaamarelainicial = float8 = Valor Inicial da Faixa Amarela 
                 g04_faixaamarelafinal = float8 = Valor Final da Faixa Amarela 
                 g04_faixavermelhainicial = float8 = Valor Inicial da Faixa Vermelha 
                 g04_faixavermelhafinal = float8 = Valor Final da Faixa Vermelha 
                 g04_emitealerta = bool = Emite Alerta 
                 g04_valoralerta = float8 = Valor para Emitir Alerta 
                 g04_emailalerta = varchar(100) = Email 
                 g04_mensagemalerta = text = Mensagem de Alerta 
                 g04_limite = date = Data de Limite de uso do Alerta 
                 g04_link = varchar(500) = Link 
                 ";
   //funcao construtor da classe 
   function cl_gestorindicador() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gestorindicador"); 
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
       $this->g04_sequencial = ($this->g04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_sequencial"]:$this->g04_sequencial);
       $this->g04_periodicidade = ($this->g04_periodicidade == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_periodicidade"]:$this->g04_periodicidade);
       $this->g04_descricao = ($this->g04_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_descricao"]:$this->g04_descricao);
       $this->g04_definicao = ($this->g04_definicao == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_definicao"]:$this->g04_definicao);
       $this->g04_tipo = ($this->g04_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_tipo"]:$this->g04_tipo);
       $this->g04_faixainicial = ($this->g04_faixainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixainicial"]:$this->g04_faixainicial);
       $this->g04_faixafinal = ($this->g04_faixafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixafinal"]:$this->g04_faixafinal);
       $this->g04_faixaverdeinicial = ($this->g04_faixaverdeinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixaverdeinicial"]:$this->g04_faixaverdeinicial);
       $this->g04_faixaverdefinal = ($this->g04_faixaverdefinal == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixaverdefinal"]:$this->g04_faixaverdefinal);
       $this->g04_faixaamarelainicial = ($this->g04_faixaamarelainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixaamarelainicial"]:$this->g04_faixaamarelainicial);
       $this->g04_faixaamarelafinal = ($this->g04_faixaamarelafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixaamarelafinal"]:$this->g04_faixaamarelafinal);
       $this->g04_faixavermelhainicial = ($this->g04_faixavermelhainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixavermelhainicial"]:$this->g04_faixavermelhainicial);
       $this->g04_faixavermelhafinal = ($this->g04_faixavermelhafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_faixavermelhafinal"]:$this->g04_faixavermelhafinal);
       $this->g04_emitealerta = ($this->g04_emitealerta == "f"?@$GLOBALS["HTTP_POST_VARS"]["g04_emitealerta"]:$this->g04_emitealerta);
       $this->g04_valoralerta = ($this->g04_valoralerta == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_valoralerta"]:$this->g04_valoralerta);
       $this->g04_emailalerta = ($this->g04_emailalerta == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_emailalerta"]:$this->g04_emailalerta);
       $this->g04_mensagemalerta = ($this->g04_mensagemalerta == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_mensagemalerta"]:$this->g04_mensagemalerta);
       if($this->g04_limite == ""){
         $this->g04_limite_dia = ($this->g04_limite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_limite_dia"]:$this->g04_limite_dia);
         $this->g04_limite_mes = ($this->g04_limite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_limite_mes"]:$this->g04_limite_mes);
         $this->g04_limite_ano = ($this->g04_limite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_limite_ano"]:$this->g04_limite_ano);
         if($this->g04_limite_dia != ""){
            $this->g04_limite = $this->g04_limite_ano."-".$this->g04_limite_mes."-".$this->g04_limite_dia;
         }
       }
       $this->g04_link = ($this->g04_link == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_link"]:$this->g04_link);
     }else{
       $this->g04_sequencial = ($this->g04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["g04_sequencial"]:$this->g04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($g04_sequencial){ 
      $this->atualizacampos();
     if($this->g04_periodicidade == null ){ 
       $this->erro_sql = " Campo Periodicidade do Indicador nao Informado.";
       $this->erro_campo = "g04_periodicidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "g04_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_definicao == null ){ 
       $this->erro_sql = " Campo Definição do Indicador nao Informado.";
       $this->erro_campo = "g04_definicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_tipo == null ){ 
       $this->erro_sql = " Campo Tipo do Indicador nao Informado.";
       $this->erro_campo = "g04_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixainicial == null ){ 
       $this->erro_sql = " Campo Faixa Inicial do Indicador nao Informado.";
       $this->erro_campo = "g04_faixainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixafinal == null ){ 
       $this->erro_sql = " Campo Faixa Final do Indicador nao Informado.";
       $this->erro_campo = "g04_faixafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixaverdeinicial == null ){ 
       $this->erro_sql = " Campo Valor Inicial da Faixa Verde nao Informado.";
       $this->erro_campo = "g04_faixaverdeinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixaverdefinal == null ){ 
       $this->erro_sql = " Campo Valor Final da Faixa Verde nao Informado.";
       $this->erro_campo = "g04_faixaverdefinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixaamarelainicial == null ){ 
       $this->erro_sql = " Campo Valor Inicial da Faixa Amarela nao Informado.";
       $this->erro_campo = "g04_faixaamarelainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixaamarelafinal == null ){ 
       $this->erro_sql = " Campo Valor Final da Faixa Amarela nao Informado.";
       $this->erro_campo = "g04_faixaamarelafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixavermelhainicial == null ){ 
       $this->erro_sql = " Campo Valor Inicial da Faixa Vermelha nao Informado.";
       $this->erro_campo = "g04_faixavermelhainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_faixavermelhafinal == null ){ 
       $this->erro_sql = " Campo Valor Final da Faixa Vermelha nao Informado.";
       $this->erro_campo = "g04_faixavermelhafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_emitealerta == null ){ 
       $this->erro_sql = " Campo Emite Alerta nao Informado.";
       $this->erro_campo = "g04_emitealerta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_valoralerta == null ){ 
       $this->erro_sql = " Campo Valor para Emitir Alerta nao Informado.";
       $this->erro_campo = "g04_valoralerta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g04_limite == null ){ 
       $this->g04_limite = "null";
     }
     if($this->g04_link == null ){ 
       $this->erro_sql = " Campo Link nao Informado.";
       $this->erro_campo = "g04_link";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($g04_sequencial == "" || $g04_sequencial == null ){
       $result = db_query("select nextval('gestorindicador_g04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: gestorindicador_g04_sequencial_seq do campo: g04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->g04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from gestorindicador_g04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $g04_sequencial)){
         $this->erro_sql = " Campo g04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->g04_sequencial = $g04_sequencial; 
       }
     }
     if(($this->g04_sequencial == null) || ($this->g04_sequencial == "") ){ 
       $this->erro_sql = " Campo g04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gestorindicador(
                                       g04_sequencial 
                                      ,g04_periodicidade 
                                      ,g04_descricao 
                                      ,g04_definicao 
                                      ,g04_tipo 
                                      ,g04_faixainicial 
                                      ,g04_faixafinal 
                                      ,g04_faixaverdeinicial 
                                      ,g04_faixaverdefinal 
                                      ,g04_faixaamarelainicial 
                                      ,g04_faixaamarelafinal 
                                      ,g04_faixavermelhainicial 
                                      ,g04_faixavermelhafinal 
                                      ,g04_emitealerta 
                                      ,g04_valoralerta 
                                      ,g04_emailalerta 
                                      ,g04_mensagemalerta 
                                      ,g04_limite 
                                      ,g04_link 
                       )
                values (
                                $this->g04_sequencial 
                               ,$this->g04_periodicidade 
                               ,'$this->g04_descricao' 
                               ,'$this->g04_definicao' 
                               ,$this->g04_tipo 
                               ,$this->g04_faixainicial 
                               ,$this->g04_faixafinal 
                               ,$this->g04_faixaverdeinicial 
                               ,$this->g04_faixaverdefinal 
                               ,$this->g04_faixaamarelainicial 
                               ,$this->g04_faixaamarelafinal 
                               ,$this->g04_faixavermelhainicial 
                               ,$this->g04_faixavermelhafinal 
                               ,'$this->g04_emitealerta' 
                               ,$this->g04_valoralerta 
                               ,'$this->g04_emailalerta' 
                               ,'$this->g04_mensagemalerta' 
                               ,".($this->g04_limite == "null" || $this->g04_limite == ""?"null":"'".$this->g04_limite."'")." 
                               ,'$this->g04_link' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "gestorindicador ($this->g04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "gestorindicador já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "gestorindicador ($this->g04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->g04_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16051,'$this->g04_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2817,16051,'','".AddSlashes(pg_result($resaco,0,'g04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16052,'','".AddSlashes(pg_result($resaco,0,'g04_periodicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16053,'','".AddSlashes(pg_result($resaco,0,'g04_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16054,'','".AddSlashes(pg_result($resaco,0,'g04_definicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16055,'','".AddSlashes(pg_result($resaco,0,'g04_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16056,'','".AddSlashes(pg_result($resaco,0,'g04_faixainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16057,'','".AddSlashes(pg_result($resaco,0,'g04_faixafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16058,'','".AddSlashes(pg_result($resaco,0,'g04_faixaverdeinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16059,'','".AddSlashes(pg_result($resaco,0,'g04_faixaverdefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16060,'','".AddSlashes(pg_result($resaco,0,'g04_faixaamarelainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16061,'','".AddSlashes(pg_result($resaco,0,'g04_faixaamarelafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16062,'','".AddSlashes(pg_result($resaco,0,'g04_faixavermelhainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16063,'','".AddSlashes(pg_result($resaco,0,'g04_faixavermelhafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16064,'','".AddSlashes(pg_result($resaco,0,'g04_emitealerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16065,'','".AddSlashes(pg_result($resaco,0,'g04_valoralerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16066,'','".AddSlashes(pg_result($resaco,0,'g04_emailalerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16067,'','".AddSlashes(pg_result($resaco,0,'g04_mensagemalerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,16068,'','".AddSlashes(pg_result($resaco,0,'g04_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2817,18189,'','".AddSlashes(pg_result($resaco,0,'g04_link'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($g04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update gestorindicador set ";
     $virgula = "";
     if(trim($this->g04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_sequencial"])){ 
       $sql  .= $virgula." g04_sequencial = $this->g04_sequencial ";
       $virgula = ",";
       if(trim($this->g04_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "g04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_periodicidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_periodicidade"])){ 
       $sql  .= $virgula." g04_periodicidade = $this->g04_periodicidade ";
       $virgula = ",";
       if(trim($this->g04_periodicidade) == null ){ 
         $this->erro_sql = " Campo Periodicidade do Indicador nao Informado.";
         $this->erro_campo = "g04_periodicidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_descricao"])){ 
       $sql  .= $virgula." g04_descricao = '$this->g04_descricao' ";
       $virgula = ",";
       if(trim($this->g04_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "g04_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_definicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_definicao"])){ 
       $sql  .= $virgula." g04_definicao = '$this->g04_definicao' ";
       $virgula = ",";
       if(trim($this->g04_definicao) == null ){ 
         $this->erro_sql = " Campo Definição do Indicador nao Informado.";
         $this->erro_campo = "g04_definicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_tipo"])){ 
       $sql  .= $virgula." g04_tipo = $this->g04_tipo ";
       $virgula = ",";
       if(trim($this->g04_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo do Indicador nao Informado.";
         $this->erro_campo = "g04_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixainicial"])){ 
       $sql  .= $virgula." g04_faixainicial = $this->g04_faixainicial ";
       $virgula = ",";
       if(trim($this->g04_faixainicial) == null ){ 
         $this->erro_sql = " Campo Faixa Inicial do Indicador nao Informado.";
         $this->erro_campo = "g04_faixainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixafinal"])){ 
       $sql  .= $virgula." g04_faixafinal = $this->g04_faixafinal ";
       $virgula = ",";
       if(trim($this->g04_faixafinal) == null ){ 
         $this->erro_sql = " Campo Faixa Final do Indicador nao Informado.";
         $this->erro_campo = "g04_faixafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixaverdeinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaverdeinicial"])){ 
       $sql  .= $virgula." g04_faixaverdeinicial = $this->g04_faixaverdeinicial ";
       $virgula = ",";
       if(trim($this->g04_faixaverdeinicial) == null ){ 
         $this->erro_sql = " Campo Valor Inicial da Faixa Verde nao Informado.";
         $this->erro_campo = "g04_faixaverdeinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixaverdefinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaverdefinal"])){ 
       $sql  .= $virgula." g04_faixaverdefinal = $this->g04_faixaverdefinal ";
       $virgula = ",";
       if(trim($this->g04_faixaverdefinal) == null ){ 
         $this->erro_sql = " Campo Valor Final da Faixa Verde nao Informado.";
         $this->erro_campo = "g04_faixaverdefinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixaamarelainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaamarelainicial"])){ 
       $sql  .= $virgula." g04_faixaamarelainicial = $this->g04_faixaamarelainicial ";
       $virgula = ",";
       if(trim($this->g04_faixaamarelainicial) == null ){ 
         $this->erro_sql = " Campo Valor Inicial da Faixa Amarela nao Informado.";
         $this->erro_campo = "g04_faixaamarelainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixaamarelafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaamarelafinal"])){ 
       $sql  .= $virgula." g04_faixaamarelafinal = $this->g04_faixaamarelafinal ";
       $virgula = ",";
       if(trim($this->g04_faixaamarelafinal) == null ){ 
         $this->erro_sql = " Campo Valor Final da Faixa Amarela nao Informado.";
         $this->erro_campo = "g04_faixaamarelafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixavermelhainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixavermelhainicial"])){ 
       $sql  .= $virgula." g04_faixavermelhainicial = $this->g04_faixavermelhainicial ";
       $virgula = ",";
       if(trim($this->g04_faixavermelhainicial) == null ){ 
         $this->erro_sql = " Campo Valor Inicial da Faixa Vermelha nao Informado.";
         $this->erro_campo = "g04_faixavermelhainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_faixavermelhafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_faixavermelhafinal"])){ 
       $sql  .= $virgula." g04_faixavermelhafinal = $this->g04_faixavermelhafinal ";
       $virgula = ",";
       if(trim($this->g04_faixavermelhafinal) == null ){ 
         $this->erro_sql = " Campo Valor Final da Faixa Vermelha nao Informado.";
         $this->erro_campo = "g04_faixavermelhafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_emitealerta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_emitealerta"])){ 
       $sql  .= $virgula." g04_emitealerta = '$this->g04_emitealerta' ";
       $virgula = ",";
       if(trim($this->g04_emitealerta) == null ){ 
         $this->erro_sql = " Campo Emite Alerta nao Informado.";
         $this->erro_campo = "g04_emitealerta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_valoralerta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_valoralerta"])){ 
       $sql  .= $virgula." g04_valoralerta = $this->g04_valoralerta ";
       $virgula = ",";
       if(trim($this->g04_valoralerta) == null ){ 
         $this->erro_sql = " Campo Valor para Emitir Alerta nao Informado.";
         $this->erro_campo = "g04_valoralerta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g04_emailalerta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_emailalerta"])){ 
       $sql  .= $virgula." g04_emailalerta = '$this->g04_emailalerta' ";
       $virgula = ",";
     }
     if(trim($this->g04_mensagemalerta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_mensagemalerta"])){ 
       $sql  .= $virgula." g04_mensagemalerta = '$this->g04_mensagemalerta' ";
       $virgula = ",";
     }
     if(trim($this->g04_limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_limite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["g04_limite_dia"] !="") ){ 
       $sql  .= $virgula." g04_limite = '$this->g04_limite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["g04_limite_dia"])){ 
         $sql  .= $virgula." g04_limite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->g04_link)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g04_link"])){ 
       $sql  .= $virgula." g04_link = '$this->g04_link' ";
       $virgula = ",";
       if(trim($this->g04_link) == null ){ 
         $this->erro_sql = " Campo Link nao Informado.";
         $this->erro_campo = "g04_link";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($g04_sequencial!=null){
       $sql .= " g04_sequencial = $this->g04_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->g04_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16051,'$this->g04_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_sequencial"]) || $this->g04_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2817,16051,'".AddSlashes(pg_result($resaco,$conresaco,'g04_sequencial'))."','$this->g04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_periodicidade"]) || $this->g04_periodicidade != "")
           $resac = db_query("insert into db_acount values($acount,2817,16052,'".AddSlashes(pg_result($resaco,$conresaco,'g04_periodicidade'))."','$this->g04_periodicidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_descricao"]) || $this->g04_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2817,16053,'".AddSlashes(pg_result($resaco,$conresaco,'g04_descricao'))."','$this->g04_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_definicao"]) || $this->g04_definicao != "")
           $resac = db_query("insert into db_acount values($acount,2817,16054,'".AddSlashes(pg_result($resaco,$conresaco,'g04_definicao'))."','$this->g04_definicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_tipo"]) || $this->g04_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2817,16055,'".AddSlashes(pg_result($resaco,$conresaco,'g04_tipo'))."','$this->g04_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixainicial"]) || $this->g04_faixainicial != "")
           $resac = db_query("insert into db_acount values($acount,2817,16056,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixainicial'))."','$this->g04_faixainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixafinal"]) || $this->g04_faixafinal != "")
           $resac = db_query("insert into db_acount values($acount,2817,16057,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixafinal'))."','$this->g04_faixafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaverdeinicial"]) || $this->g04_faixaverdeinicial != "")
           $resac = db_query("insert into db_acount values($acount,2817,16058,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixaverdeinicial'))."','$this->g04_faixaverdeinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaverdefinal"]) || $this->g04_faixaverdefinal != "")
           $resac = db_query("insert into db_acount values($acount,2817,16059,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixaverdefinal'))."','$this->g04_faixaverdefinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaamarelainicial"]) || $this->g04_faixaamarelainicial != "")
           $resac = db_query("insert into db_acount values($acount,2817,16060,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixaamarelainicial'))."','$this->g04_faixaamarelainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixaamarelafinal"]) || $this->g04_faixaamarelafinal != "")
           $resac = db_query("insert into db_acount values($acount,2817,16061,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixaamarelafinal'))."','$this->g04_faixaamarelafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixavermelhainicial"]) || $this->g04_faixavermelhainicial != "")
           $resac = db_query("insert into db_acount values($acount,2817,16062,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixavermelhainicial'))."','$this->g04_faixavermelhainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_faixavermelhafinal"]) || $this->g04_faixavermelhafinal != "")
           $resac = db_query("insert into db_acount values($acount,2817,16063,'".AddSlashes(pg_result($resaco,$conresaco,'g04_faixavermelhafinal'))."','$this->g04_faixavermelhafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_emitealerta"]) || $this->g04_emitealerta != "")
           $resac = db_query("insert into db_acount values($acount,2817,16064,'".AddSlashes(pg_result($resaco,$conresaco,'g04_emitealerta'))."','$this->g04_emitealerta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_valoralerta"]) || $this->g04_valoralerta != "")
           $resac = db_query("insert into db_acount values($acount,2817,16065,'".AddSlashes(pg_result($resaco,$conresaco,'g04_valoralerta'))."','$this->g04_valoralerta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_emailalerta"]) || $this->g04_emailalerta != "")
           $resac = db_query("insert into db_acount values($acount,2817,16066,'".AddSlashes(pg_result($resaco,$conresaco,'g04_emailalerta'))."','$this->g04_emailalerta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_mensagemalerta"]) || $this->g04_mensagemalerta != "")
           $resac = db_query("insert into db_acount values($acount,2817,16067,'".AddSlashes(pg_result($resaco,$conresaco,'g04_mensagemalerta'))."','$this->g04_mensagemalerta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_limite"]) || $this->g04_limite != "")
           $resac = db_query("insert into db_acount values($acount,2817,16068,'".AddSlashes(pg_result($resaco,$conresaco,'g04_limite'))."','$this->g04_limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g04_link"]) || $this->g04_link != "")
           $resac = db_query("insert into db_acount values($acount,2817,18189,'".AddSlashes(pg_result($resaco,$conresaco,'g04_link'))."','$this->g04_link',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "gestorindicador nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->g04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "gestorindicador nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->g04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($g04_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($g04_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16051,'$g04_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2817,16051,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16052,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_periodicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16053,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16054,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_definicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16055,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16056,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16057,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16058,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixaverdeinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16059,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixaverdefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16060,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixaamarelainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16061,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixaamarelafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16062,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixavermelhainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16063,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_faixavermelhafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16064,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_emitealerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16065,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_valoralerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16066,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_emailalerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16067,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_mensagemalerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,16068,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2817,18189,'','".AddSlashes(pg_result($resaco,$iresaco,'g04_link'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gestorindicador
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($g04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " g04_sequencial = $g04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "gestorindicador nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$g04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "gestorindicador nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$g04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$g04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:gestorindicador";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $g04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gestorindicador ";
     $sql .= "      inner join db_periodicidade  on  db_periodicidade.db84_sequencial = gestorindicador.g04_periodicidade";
     $sql2 = "";
     if($dbwhere==""){
       if($g04_sequencial!=null ){
         $sql2 .= " where gestorindicador.g04_sequencial = $g04_sequencial "; 
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
   function sql_query_file ( $g04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gestorindicador ";
     $sql2 = "";
     if($dbwhere==""){
       if($g04_sequencial!=null ){
         $sql2 .= " where gestorindicador.g04_sequencial = $g04_sequencial "; 
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