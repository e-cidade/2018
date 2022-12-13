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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoitemprevisao
class cl_acordoitemprevisao { 
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
   var $ac37_sequencial = 0; 
   var $ac37_acordoitem = 0; 
   var $ac37_quantidade = 0; 
   var $ac37_valor = 0; 
   var $ac37_acordoperiodo = 0; 
   var $ac37_datainicial_dia = null; 
   var $ac37_datainicial_mes = null; 
   var $ac37_datainicial_ano = null; 
   var $ac37_datainicial = null; 
   var $ac37_datafinal_dia = null; 
   var $ac37_datafinal_mes = null; 
   var $ac37_datafinal_ano = null; 
   var $ac37_datafinal = null; 
   var $ac37_quantidadeprevista = 0; 
   var $ac37_valorunitario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac37_sequencial = int4 = Codigo_sequencial 
                 ac37_acordoitem = int4 = Item Acordo 
                 ac37_quantidade = float4 = Quantidade 
                 ac37_valor = float4 = valor 
                 ac37_acordoperiodo = int4 = Acordo Perido 
                 ac37_datainicial = date = Data inicial 
                 ac37_datafinal = date = Data final 
                 ac37_quantidadeprevista = float8 = Quanitdade Prevista 
                 ac37_valorunitario = float8 = Valor Unitário 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemprevisao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemprevisao"); 
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
       $this->ac37_sequencial = ($this->ac37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_sequencial"]:$this->ac37_sequencial);
       $this->ac37_acordoitem = ($this->ac37_acordoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_acordoitem"]:$this->ac37_acordoitem);
       $this->ac37_quantidade = ($this->ac37_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"]:$this->ac37_quantidade);
       $this->ac37_valor = ($this->ac37_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_valor"]:$this->ac37_valor);
       $this->ac37_acordoperiodo = ($this->ac37_acordoperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_acordoperiodo"]:$this->ac37_acordoperiodo);
       if($this->ac37_datainicial == ""){
         $this->ac37_datainicial_dia = ($this->ac37_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"]:$this->ac37_datainicial_dia);
         $this->ac37_datainicial_mes = ($this->ac37_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_mes"]:$this->ac37_datainicial_mes);
         $this->ac37_datainicial_ano = ($this->ac37_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_ano"]:$this->ac37_datainicial_ano);
         if($this->ac37_datainicial_dia != ""){
            $this->ac37_datainicial = $this->ac37_datainicial_ano."-".$this->ac37_datainicial_mes."-".$this->ac37_datainicial_dia;
         }
       }
       if($this->ac37_datafinal == ""){
         $this->ac37_datafinal_dia = ($this->ac37_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"]:$this->ac37_datafinal_dia);
         $this->ac37_datafinal_mes = ($this->ac37_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_mes"]:$this->ac37_datafinal_mes);
         $this->ac37_datafinal_ano = ($this->ac37_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_ano"]:$this->ac37_datafinal_ano);
         if($this->ac37_datafinal_dia != ""){
            $this->ac37_datafinal = $this->ac37_datafinal_ano."-".$this->ac37_datafinal_mes."-".$this->ac37_datafinal_dia;
         }
       }
       $this->ac37_quantidadeprevista = ($this->ac37_quantidadeprevista == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"]:$this->ac37_quantidadeprevista);
       $this->ac37_valorunitario = ($this->ac37_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"]:$this->ac37_valorunitario);
     }else{
       $this->ac37_sequencial = ($this->ac37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac37_sequencial"]:$this->ac37_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac37_sequencial){ 
      $this->atualizacampos();
     if($this->ac37_acordoitem == null ){ 
       $this->erro_sql = " Campo Item Acordo nao Informado.";
       $this->erro_campo = "ac37_acordoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac37_quantidade == null ){ 
       $this->ac37_quantidade = "0";
     }
     if($this->ac37_valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "ac37_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac37_acordoperiodo == null ){ 
       $this->erro_sql = " Campo Acordo Perido nao Informado.";
       $this->erro_campo = "ac37_acordoperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac37_datainicial == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "ac37_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac37_datafinal == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "ac37_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac37_quantidadeprevista == null ){ 
       $this->ac37_quantidadeprevista = "0";
     }
     if($this->ac37_valorunitario == null ){ 
       $this->ac37_valorunitario = "0";
     }
     if($ac37_sequencial == "" || $ac37_sequencial == null ){
       $result = db_query("select nextval('acordoitemprevisao_ac37_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemprevisao_ac37_sequencial_seq do campo: ac37_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac37_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemprevisao_ac37_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac37_sequencial)){
         $this->erro_sql = " Campo ac37_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac37_sequencial = $ac37_sequencial; 
       }
     }
     if(($this->ac37_sequencial == null) || ($this->ac37_sequencial == "") ){ 
       $this->erro_sql = " Campo ac37_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemprevisao(
                                       ac37_sequencial 
                                      ,ac37_acordoitem 
                                      ,ac37_quantidade 
                                      ,ac37_valor 
                                      ,ac37_acordoperiodo 
                                      ,ac37_datainicial 
                                      ,ac37_datafinal 
                                      ,ac37_quantidadeprevista 
                                      ,ac37_valorunitario 
                       )
                values (
                                $this->ac37_sequencial 
                               ,$this->ac37_acordoitem 
                               ,$this->ac37_quantidade 
                               ,$this->ac37_valor 
                               ,$this->ac37_acordoperiodo 
                               ,".($this->ac37_datainicial == "null" || $this->ac37_datainicial == ""?"null":"'".$this->ac37_datainicial."'")." 
                               ,".($this->ac37_datafinal == "null" || $this->ac37_datafinal == ""?"null":"'".$this->ac37_datafinal."'")." 
                               ,$this->ac37_quantidadeprevista 
                               ,$this->ac37_valorunitario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "acordoitemprevisao ($this->ac37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "acordoitemprevisao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "acordoitemprevisao ($this->ac37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac37_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18041,'$this->ac37_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3188,18041,'','".AddSlashes(pg_result($resaco,0,'ac37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18042,'','".AddSlashes(pg_result($resaco,0,'ac37_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18043,'','".AddSlashes(pg_result($resaco,0,'ac37_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18044,'','".AddSlashes(pg_result($resaco,0,'ac37_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18045,'','".AddSlashes(pg_result($resaco,0,'ac37_acordoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18046,'','".AddSlashes(pg_result($resaco,0,'ac37_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18047,'','".AddSlashes(pg_result($resaco,0,'ac37_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18077,'','".AddSlashes(pg_result($resaco,0,'ac37_quantidadeprevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3188,18107,'','".AddSlashes(pg_result($resaco,0,'ac37_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac37_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemprevisao set ";
     $virgula = "";
     if(trim($this->ac37_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_sequencial"])){ 
       $sql  .= $virgula." ac37_sequencial = $this->ac37_sequencial ";
       $virgula = ",";
       if(trim($this->ac37_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo_sequencial nao Informado.";
         $this->erro_campo = "ac37_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_acordoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoitem"])){ 
       $sql  .= $virgula." ac37_acordoitem = $this->ac37_acordoitem ";
       $virgula = ",";
       if(trim($this->ac37_acordoitem) == null ){ 
         $this->erro_sql = " Campo Item Acordo nao Informado.";
         $this->erro_campo = "ac37_acordoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"])){ 
        if(trim($this->ac37_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"])){ 
           $this->ac37_quantidade = "0" ; 
        } 
       $sql  .= $virgula." ac37_quantidade = $this->ac37_quantidade ";
       $virgula = ",";
     }
     if(trim($this->ac37_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_valor"])){ 
       $sql  .= $virgula." ac37_valor = $this->ac37_valor ";
       $virgula = ",";
       if(trim($this->ac37_valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "ac37_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_acordoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoperiodo"])){ 
       $sql  .= $virgula." ac37_acordoperiodo = $this->ac37_acordoperiodo ";
       $virgula = ",";
       if(trim($this->ac37_acordoperiodo) == null ){ 
         $this->erro_sql = " Campo Acordo Perido nao Informado.";
         $this->erro_campo = "ac37_acordoperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ac37_datainicial = '$this->ac37_datainicial' ";
       $virgula = ",";
       if(trim($this->ac37_datainicial) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "ac37_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"])){ 
         $sql  .= $virgula." ac37_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac37_datainicial) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "ac37_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac37_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ac37_datafinal = '$this->ac37_datafinal' ";
       $virgula = ",";
       if(trim($this->ac37_datafinal) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "ac37_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"])){ 
         $sql  .= $virgula." ac37_datafinal = null ";
         $virgula = ",";
         if(trim($this->ac37_datafinal) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "ac37_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac37_quantidadeprevista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"])){ 
        if(trim($this->ac37_quantidadeprevista)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"])){ 
           $this->ac37_quantidadeprevista = "0" ; 
        } 
       $sql  .= $virgula." ac37_quantidadeprevista = $this->ac37_quantidadeprevista ";
       $virgula = ",";
     }
     if(trim($this->ac37_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"])){ 
        if(trim($this->ac37_valorunitario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"])){ 
           $this->ac37_valorunitario = "0" ; 
        } 
       $sql  .= $virgula." ac37_valorunitario = $this->ac37_valorunitario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac37_sequencial!=null){
       $sql .= " ac37_sequencial = $this->ac37_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac37_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18041,'$this->ac37_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_sequencial"]) || $this->ac37_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3188,18041,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_sequencial'))."','$this->ac37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoitem"]) || $this->ac37_acordoitem != "")
           $resac = db_query("insert into db_acount values($acount,3188,18042,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_acordoitem'))."','$this->ac37_acordoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"]) || $this->ac37_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3188,18043,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_quantidade'))."','$this->ac37_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_valor"]) || $this->ac37_valor != "")
           $resac = db_query("insert into db_acount values($acount,3188,18044,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_valor'))."','$this->ac37_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoperiodo"]) || $this->ac37_acordoperiodo != "")
           $resac = db_query("insert into db_acount values($acount,3188,18045,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_acordoperiodo'))."','$this->ac37_acordoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial"]) || $this->ac37_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3188,18046,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_datainicial'))."','$this->ac37_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal"]) || $this->ac37_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3188,18047,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_datafinal'))."','$this->ac37_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"]) || $this->ac37_quantidadeprevista != "")
           $resac = db_query("insert into db_acount values($acount,3188,18077,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_quantidadeprevista'))."','$this->ac37_quantidadeprevista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"]) || $this->ac37_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,3188,18107,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_valorunitario'))."','$this->ac37_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemprevisao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemprevisao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }

   function alterar_where ($sWhereAlterar) {
     
     if (empty($sWhereAlterar)) {
       throw new Exception("Condição de exclusão não informada.");
     }
     $this->atualizacampos();
     $sql = " update acordoitemprevisao set ";
     $virgula = "";
     if(trim($this->ac37_acordoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoitem"])){
       $sql  .= $virgula." ac37_acordoitem = $this->ac37_acordoitem ";
       $virgula = ",";
       if(trim($this->ac37_acordoitem) == null ){
         $this->erro_sql = " Campo Item Acordo nao Informado.";
         $this->erro_campo = "ac37_acordoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"])){
       if(trim($this->ac37_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"])){
         $this->ac37_quantidade = "0" ;
       }
       $sql  .= $virgula." ac37_quantidade = $this->ac37_quantidade ";
       $virgula = ",";
     }
     if(trim($this->ac37_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_valor"])){
       $sql  .= $virgula." ac37_valor = $this->ac37_valor ";
       $virgula = ",";
       if(trim($this->ac37_valor) == null ){
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "ac37_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_acordoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoperiodo"])){
       $sql  .= $virgula." ac37_acordoperiodo = $this->ac37_acordoperiodo ";
       $virgula = ",";
       if(trim($this->ac37_acordoperiodo) == null ){
         $this->erro_sql = " Campo Acordo Perido nao Informado.";
         $this->erro_campo = "ac37_acordoperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac37_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"] !="") ){
       $sql  .= $virgula." ac37_datainicial = '$this->ac37_datainicial' ";
       $virgula = ",";
       if(trim($this->ac37_datainicial) == null ){
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "ac37_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial_dia"])){
         $sql  .= $virgula." ac37_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac37_datainicial) == null ){
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "ac37_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac37_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"] !="") ){
       $sql  .= $virgula." ac37_datafinal = '$this->ac37_datafinal' ";
       $virgula = ",";
       if(trim($this->ac37_datafinal) == null ){
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "ac37_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal_dia"])){
         $sql  .= $virgula." ac37_datafinal = null ";
         $virgula = ",";
         if(trim($this->ac37_datafinal) == null ){
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "ac37_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac37_quantidadeprevista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"])){
       if(trim($this->ac37_quantidadeprevista)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"])){
         $this->ac37_quantidadeprevista = "0" ;
       }
       $sql  .= $virgula." ac37_quantidadeprevista = $this->ac37_quantidadeprevista ";
       $virgula = ",";
     }
     if(trim($this->ac37_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"])){
       if(trim($this->ac37_valorunitario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"])){
         $this->ac37_valorunitario = "0" ;
       }
       $sql  .= $virgula." ac37_valorunitario = $this->ac37_valorunitario ";
       $virgula = ",";
     }
     $ac37_sequencial = null;
     if($ac37_sequencial!=null){
       $sql .= " ac37_sequencial = $this->ac37_sequencial";
     }
     
     $sql .= " where {$sWhereAlterar}";
     
     $resaco = $this->sql_record($this->sql_query_file($this->ac37_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18041,'$this->ac37_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_sequencial"]) || $this->ac37_sequencial != "")
         $resac = db_query("insert into db_acount values($acount,3188,18041,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_sequencial'))."','$this->ac37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoitem"]) || $this->ac37_acordoitem != "")
         $resac = db_query("insert into db_acount values($acount,3188,18042,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_acordoitem'))."','$this->ac37_acordoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidade"]) || $this->ac37_quantidade != "")
         $resac = db_query("insert into db_acount values($acount,3188,18043,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_quantidade'))."','$this->ac37_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_valor"]) || $this->ac37_valor != "")
         $resac = db_query("insert into db_acount values($acount,3188,18044,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_valor'))."','$this->ac37_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_acordoperiodo"]) || $this->ac37_acordoperiodo != "")
         $resac = db_query("insert into db_acount values($acount,3188,18045,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_acordoperiodo'))."','$this->ac37_acordoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datainicial"]) || $this->ac37_datainicial != "")
         $resac = db_query("insert into db_acount values($acount,3188,18046,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_datainicial'))."','$this->ac37_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_datafinal"]) || $this->ac37_datafinal != "")
         $resac = db_query("insert into db_acount values($acount,3188,18047,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_datafinal'))."','$this->ac37_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_quantidadeprevista"]) || $this->ac37_quantidadeprevista != "")
         $resac = db_query("insert into db_acount values($acount,3188,18077,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_quantidadeprevista'))."','$this->ac37_quantidadeprevista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac37_valorunitario"]) || $this->ac37_valorunitario != "")
         $resac = db_query("insert into db_acount values($acount,3188,18107,'".AddSlashes(pg_result($resaco,$conresaco,'ac37_valorunitario'))."','$this->ac37_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemprevisao nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemprevisao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   
   // funcao para exclusao 
   function excluir ($ac37_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac37_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18041,'$ac37_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3188,18041,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18042,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18043,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18044,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18045,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_acordoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18046,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18047,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18077,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_quantidadeprevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3188,18107,'','".AddSlashes(pg_result($resaco,$iresaco,'ac37_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemprevisao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac37_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac37_sequencial = $ac37_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemprevisao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemprevisao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac37_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemprevisao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemprevisao ";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemprevisao.ac37_acordoitem";
     $sql .= "      inner join acordoposicaoperiodo  on  acordoposicaoperiodo.ac36_sequencial = acordoitemprevisao.ac37_acordoperiodo";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  as a on   a.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql .= "      inner join acordoposicao  as b on   b.ac26_sequencial = acordoposicaoperiodo.ac36_acordoposicao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac37_sequencial!=null ){
         $sql2 .= " where acordoitemprevisao.ac37_sequencial = $ac37_sequencial "; 
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
   function sql_query_file ( $ac37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemprevisao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac37_sequencial!=null ){
         $sql2 .= " where acordoitemprevisao.ac37_sequencial = $ac37_sequencial "; 
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
   function sql_query_execucao ( $ac38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoperiodo ";
     $sql .= "      inner join acordoitemexecutado  on  acordoitemexecutado.ac29_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
     $sql .= "      inner join acordoitemprevisao  on  acordoitemprevisao.ac37_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemprevisao";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemprevisao.ac37_acordoitem";
     $sql2 = "";
     if($dbwhere==""){
       if($ac38_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoperiodo.ac38_sequencial = $ac38_sequencial "; 
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
  
  
  
  function sql_queryItemPeriodoPrevisao ( $ac37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from acordoitemprevisao ";
    $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemprevisao.ac37_acordoitem";
    $sql .= "      inner join acordoposicaoperiodo  on  acordoposicaoperiodo.ac36_sequencial = acordoitemprevisao.ac37_acordoperiodo";
    $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
    $sql .= "      inner join acordoposicao  as a on   a.ac26_sequencial = acordoitem.ac20_acordoposicao";
    $sql .= "      inner join acordoposicao  as b on   b.ac26_sequencial = acordoposicaoperiodo.ac36_acordoposicao";
    $sql .= "      inner join acordoitemperiodo on acordoitem.ac20_sequencial = acordoitemperiodo.ac41_acordoitem";
    $sql2 = "";
    if($dbwhere==""){
      if($ac37_sequencial!=null ){
        $sql2 .= " where acordoitemprevisao.ac37_sequencial = $ac37_sequencial ";
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