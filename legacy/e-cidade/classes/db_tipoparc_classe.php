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

//MODULO: divida
//CLASSE DA ENTIDADE tipoparc
class cl_tipoparc { 
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
   var $tipoparc = 0; 
   var $descr = null; 
   var $dtini_dia = null; 
   var $dtini_mes = null; 
   var $dtini_ano = null; 
   var $dtini = null; 
   var $dtfim_dia = null; 
   var $dtfim_mes = null; 
   var $dtfim_ano = null; 
   var $dtfim = null; 
   var $maxparc = 0; 
   var $minparc = 0;   
   var $vlrmin = 0; 
   var $dtvlr_dia = null; 
   var $dtvlr_mes = null; 
   var $dtvlr_ano = null; 
   var $dtvlr = null; 
   var $inflat = null; 
   var $descmul = 0; 
   var $descjur = 0; 
   var $descvlr = 0; 
   var $cadtipoparc = 0; 
   var $k42_minentrada = 0; 
   var $instit = 0; 
   var $tipovlr = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tipoparc = int4 = Tipo de Parcelamento 
                 descr = varchar(40) = Descrição 
                 dtini = date = Data Inicial 
                 dtfim = date = Data Final 
                 maxparc = int4 = Máximo de Parcelas
                 minparc = int4 = Minimo de Parcelas
                 vlrmin = float8 = valor minimo 
                 dtvlr = date = data do valor minimo 
                 inflat = varchar(5) = inflator 
                 descmul = float8 = Desconto Multa (%) 
                 descjur = float8 = Desconto Juros (%) 
                 descvlr = float8 = Desconto Valor Corrigido (%) 
                 cadtipoparc = int4 = Código 
                 k42_minentrada = float8 = Mínimo na Entrada (%) 
                 instit = int4 = Instituição 
                 tipovlr = int4 = Tipo de valor para correção 
                 ";
   //funcao construtor da classe 
   function cl_tipoparc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoparc"); 
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
       $this->tipoparc = ($this->tipoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["tipoparc"]:$this->tipoparc);
       $this->descr = ($this->descr == ""?@$GLOBALS["HTTP_POST_VARS"]["descr"]:$this->descr);
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
       $this->maxparc = ($this->maxparc == ""?@$GLOBALS["HTTP_POST_VARS"]["maxparc"]:$this->maxparc);
       $this->minparc = ($this->minparc == ""?@$GLOBALS["HTTP_POST_VARS"]["minparc"]:$this->minparc);       
       $this->vlrmin = ($this->vlrmin == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrmin"]:$this->vlrmin);
       if($this->dtvlr == ""){
         $this->dtvlr_dia = ($this->dtvlr_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtvlr_dia"]:$this->dtvlr_dia);
         $this->dtvlr_mes = ($this->dtvlr_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtvlr_mes"]:$this->dtvlr_mes);
         $this->dtvlr_ano = ($this->dtvlr_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtvlr_ano"]:$this->dtvlr_ano);
         if($this->dtvlr_dia != ""){
            $this->dtvlr = $this->dtvlr_ano."-".$this->dtvlr_mes."-".$this->dtvlr_dia;
         }
       }
       $this->inflat = ($this->inflat == ""?@$GLOBALS["HTTP_POST_VARS"]["inflat"]:$this->inflat);
       $this->descmul = ($this->descmul == ""?@$GLOBALS["HTTP_POST_VARS"]["descmul"]:$this->descmul);
       $this->descjur = ($this->descjur == ""?@$GLOBALS["HTTP_POST_VARS"]["descjur"]:$this->descjur);
       $this->descvlr = ($this->descvlr == ""?@$GLOBALS["HTTP_POST_VARS"]["descvlr"]:$this->descvlr);
       $this->cadtipoparc = ($this->cadtipoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["cadtipoparc"]:$this->cadtipoparc);
       $this->k42_minentrada = ($this->k42_minentrada == ""?@$GLOBALS["HTTP_POST_VARS"]["k42_minentrada"]:$this->k42_minentrada);
       $this->instit = ($this->instit == ""?@$GLOBALS["HTTP_POST_VARS"]["instit"]:$this->instit);
       $this->tipovlr = ($this->tipovlr == ""?@$GLOBALS["HTTP_POST_VARS"]["tipovlr"]:$this->tipovlr);
     }else{
       $this->tipoparc = ($this->tipoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["tipoparc"]:$this->tipoparc);
     }
   }
   // funcao para inclusao
   function incluir ($tipoparc){ 
      $this->atualizacampos();
     if($this->descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "descr";
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
     if($this->maxparc == null ){ 
       $this->erro_sql = " Campo Máximo de Parcelas nao Informado.";
       $this->erro_campo = "maxparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->minparc == null ){ 
       $this->erro_sql = " Campo Minimo de Parcelas nao Informado.";
       $this->erro_campo = "minparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }     
     if($this->vlrmin == null ){ 
       $this->erro_sql = " Campo valor minimo nao Informado.";
       $this->erro_campo = "vlrmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtvlr == null ){ 
       $this->erro_sql = " Campo data do valor minimo nao Informado.";
       $this->erro_campo = "dtvlr_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->inflat == null ){ 
       $this->erro_sql = " Campo inflator nao Informado.";
       $this->erro_campo = "inflat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->descmul == null ){ 
       $this->erro_sql = " Campo Desconto Multa (%) nao Informado.";
       $this->erro_campo = "descmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->descjur == null ){ 
       $this->erro_sql = " Campo Desconto Juros (%) nao Informado.";
       $this->erro_campo = "descjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->descvlr == null ){ 
       $this->erro_sql = " Campo Desconto Valor Corrigido (%) nao Informado.";
       $this->erro_campo = "descvlr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cadtipoparc == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "cadtipoparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k42_minentrada == null ){ 
       $this->erro_sql = " Campo Mínimo na Entrada (%) nao Informado.";
       $this->erro_campo = "k42_minentrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tipovlr == null ){ 
       $this->erro_sql = " Campo Tipo de valor para correção nao Informado.";
       $this->erro_campo = "tipovlr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tipoparc == "" || $tipoparc == null ){
       $result = db_query("select nextval('tipoparc_tipoparc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoparc_tipoparc_seq do campo: tipoparc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tipoparc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoparc_tipoparc_seq");
       if(($result != false) && (pg_result($result,0,0) < $tipoparc)){
         $this->erro_sql = " Campo tipoparc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tipoparc = $tipoparc; 
       }
     }
     if(($this->tipoparc == null) || ($this->tipoparc == "") ){ 
       $this->erro_sql = " Campo tipoparc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoparc(
                                       tipoparc 
                                      ,descr 
                                      ,dtini 
                                      ,dtfim 
                                      ,maxparc 
                                      ,minparc
                                      ,vlrmin 
                                      ,dtvlr 
                                      ,inflat 
                                      ,descmul 
                                      ,descjur 
                                      ,descvlr 
                                      ,cadtipoparc 
                                      ,k42_minentrada 
                                      ,instit 
                                      ,tipovlr 
                       )
                values (
                                $this->tipoparc 
                               ,'$this->descr' 
                               ,".($this->dtini == "null" || $this->dtini == ""?"null":"'".$this->dtini."'")." 
                               ,".($this->dtfim == "null" || $this->dtfim == ""?"null":"'".$this->dtfim."'")." 
                               ,$this->maxparc
                               ,$this->minparc 
                               ,$this->vlrmin 
                               ,".($this->dtvlr == "null" || $this->dtvlr == ""?"null":"'".$this->dtvlr."'")." 
                               ,'$this->inflat' 
                               ,$this->descmul 
                               ,$this->descjur 
                               ,$this->descvlr 
                               ,$this->cadtipoparc 
                               ,$this->k42_minentrada 
                               ,$this->instit 
                               ,$this->tipovlr 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->tipoparc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->tipoparc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tipoparc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tipoparc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,500,'$this->tipoparc','I')");
       $resac = db_query("insert into db_acount values($acount,95,500,'','".AddSlashes(pg_result($resaco,0,'tipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,501,'','".AddSlashes(pg_result($resaco,0,'descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,502,'','".AddSlashes(pg_result($resaco,0,'dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,503,'','".AddSlashes(pg_result($resaco,0,'dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,504,'','".AddSlashes(pg_result($resaco,0,'maxparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,16693,'','".AddSlashes(pg_result($resaco,0,'minparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");       
       $resac = db_query("insert into db_acount values($acount,95,505,'','".AddSlashes(pg_result($resaco,0,'vlrmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,506,'','".AddSlashes(pg_result($resaco,0,'dtvlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,507,'','".AddSlashes(pg_result($resaco,0,'inflat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,7400,'','".AddSlashes(pg_result($resaco,0,'descmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,7399,'','".AddSlashes(pg_result($resaco,0,'descjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,9863,'','".AddSlashes(pg_result($resaco,0,'descvlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,7581,'','".AddSlashes(pg_result($resaco,0,'cadtipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,9580,'','".AddSlashes(pg_result($resaco,0,'k42_minentrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,9996,'','".AddSlashes(pg_result($resaco,0,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,95,14348,'','".AddSlashes(pg_result($resaco,0,'tipovlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tipoparc=null) { 
      $this->atualizacampos();
     $sql = " update tipoparc set ";
     $virgula = "";
     if(trim($this->tipoparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipoparc"])){ 
       $sql  .= $virgula." tipoparc = $this->tipoparc ";
       $virgula = ",";
       if(trim($this->tipoparc) == null ){ 
         $this->erro_sql = " Campo Tipo de Parcelamento nao Informado.";
         $this->erro_campo = "tipoparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descr"])){ 
       $sql  .= $virgula." descr = '$this->descr' ";
       $virgula = ",";
       if(trim($this->descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "descr";
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
     if(trim($this->maxparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["maxparc"])){ 
       $sql  .= $virgula." maxparc = $this->maxparc ";
       $virgula = ",";
       if(trim($this->maxparc) == null ){ 
         $this->erro_sql = " Campo Máximo de Parcelas nao Informado.";
         $this->erro_campo = "maxparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->minparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["minparc"])){ 
       $sql  .= $virgula." minparc = $this->minparc ";
       $virgula = ",";
       if(trim($this->minparc) == null ){ 
         $this->erro_sql = " Campo Minimo de Parcelas nao Informado.";
         $this->erro_campo = "minparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     
     if(trim($this->vlrmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrmin"])){ 
       $sql  .= $virgula." vlrmin = $this->vlrmin ";
       $virgula = ",";
       if(trim($this->vlrmin) == null ){ 
         $this->erro_sql = " Campo valor minimo nao Informado.";
         $this->erro_campo = "vlrmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dtvlr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtvlr_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtvlr_dia"] !="") ){ 
       $sql  .= $virgula." dtvlr = '$this->dtvlr' ";
       $virgula = ",";
       if(trim($this->dtvlr) == null ){ 
         $this->erro_sql = " Campo data do valor minimo nao Informado.";
         $this->erro_campo = "dtvlr_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtvlr_dia"])){ 
         $sql  .= $virgula." dtvlr = null ";
         $virgula = ",";
         if(trim($this->dtvlr) == null ){ 
           $this->erro_sql = " Campo data do valor minimo nao Informado.";
           $this->erro_campo = "dtvlr_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->inflat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["inflat"])){ 
       $sql  .= $virgula." inflat = '$this->inflat' ";
       $virgula = ",";
       if(trim($this->inflat) == null ){ 
         $this->erro_sql = " Campo inflator nao Informado.";
         $this->erro_campo = "inflat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descmul"])){ 
       $sql  .= $virgula." descmul = $this->descmul ";
       $virgula = ",";
       if(trim($this->descmul) == null ){ 
         $this->erro_sql = " Campo Desconto Multa (%) nao Informado.";
         $this->erro_campo = "descmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descjur"])){ 
       $sql  .= $virgula." descjur = $this->descjur ";
       $virgula = ",";
       if(trim($this->descjur) == null ){ 
         $this->erro_sql = " Campo Desconto Juros (%) nao Informado.";
         $this->erro_campo = "descjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descvlr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descvlr"])){ 
       $sql  .= $virgula." descvlr = $this->descvlr ";
       $virgula = ",";
       if(trim($this->descvlr) == null ){ 
         $this->erro_sql = " Campo Desconto Valor Corrigido (%) nao Informado.";
         $this->erro_campo = "descvlr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cadtipoparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cadtipoparc"])){ 
       $sql  .= $virgula." cadtipoparc = $this->cadtipoparc ";
       $virgula = ",";
       if(trim($this->cadtipoparc) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cadtipoparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k42_minentrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k42_minentrada"])){ 
       $sql  .= $virgula." k42_minentrada = $this->k42_minentrada ";
       $virgula = ",";
       if(trim($this->k42_minentrada) == null ){ 
         $this->erro_sql = " Campo Mínimo na Entrada (%) nao Informado.";
         $this->erro_campo = "k42_minentrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["instit"])){ 
       $sql  .= $virgula." instit = $this->instit ";
       $virgula = ",";
       if(trim($this->instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tipovlr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipovlr"])){ 
       $sql  .= $virgula." tipovlr = $this->tipovlr ";
       $virgula = ",";
       if(trim($this->tipovlr) == null ){ 
         $this->erro_sql = " Campo Tipo de valor para correção nao Informado.";
         $this->erro_campo = "tipovlr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tipoparc!=null){
       $sql .= " tipoparc = $this->tipoparc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tipoparc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,500,'$this->tipoparc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tipoparc"]) || $this->tipoparc != "")
           $resac = db_query("insert into db_acount values($acount,95,500,'".AddSlashes(pg_result($resaco,$conresaco,'tipoparc'))."','$this->tipoparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descr"]) || $this->descr != "")
           $resac = db_query("insert into db_acount values($acount,95,501,'".AddSlashes(pg_result($resaco,$conresaco,'descr'))."','$this->descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtini"]) || $this->dtini != "")
           $resac = db_query("insert into db_acount values($acount,95,502,'".AddSlashes(pg_result($resaco,$conresaco,'dtini'))."','$this->dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtfim"]) || $this->dtfim != "")
           $resac = db_query("insert into db_acount values($acount,95,503,'".AddSlashes(pg_result($resaco,$conresaco,'dtfim'))."','$this->dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["maxparc"]) || $this->maxparc != "")
           $resac = db_query("insert into db_acount values($acount,95,504,'".AddSlashes(pg_result($resaco,$conresaco,'maxparc'))."','$this->maxparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["minparc"]) || $this->minparc != "")          
           $resac = db_query("insert into db_acount values($acount,95,16693,'','".AddSlashes(pg_result($resaco,0,'minparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");           
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrmin"]) || $this->vlrmin != "")
           $resac = db_query("insert into db_acount values($acount,95,505,'".AddSlashes(pg_result($resaco,$conresaco,'vlrmin'))."','$this->vlrmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtvlr"]) || $this->dtvlr != "")
           $resac = db_query("insert into db_acount values($acount,95,506,'".AddSlashes(pg_result($resaco,$conresaco,'dtvlr'))."','$this->dtvlr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["inflat"]) || $this->inflat != "")
           $resac = db_query("insert into db_acount values($acount,95,507,'".AddSlashes(pg_result($resaco,$conresaco,'inflat'))."','$this->inflat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descmul"]) || $this->descmul != "")
           $resac = db_query("insert into db_acount values($acount,95,7400,'".AddSlashes(pg_result($resaco,$conresaco,'descmul'))."','$this->descmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descjur"]) || $this->descjur != "")
           $resac = db_query("insert into db_acount values($acount,95,7399,'".AddSlashes(pg_result($resaco,$conresaco,'descjur'))."','$this->descjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descvlr"]) || $this->descvlr != "")
           $resac = db_query("insert into db_acount values($acount,95,9863,'".AddSlashes(pg_result($resaco,$conresaco,'descvlr'))."','$this->descvlr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cadtipoparc"]) || $this->cadtipoparc != "")
           $resac = db_query("insert into db_acount values($acount,95,7581,'".AddSlashes(pg_result($resaco,$conresaco,'cadtipoparc'))."','$this->cadtipoparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k42_minentrada"]) || $this->k42_minentrada != "")
           $resac = db_query("insert into db_acount values($acount,95,9580,'".AddSlashes(pg_result($resaco,$conresaco,'k42_minentrada'))."','$this->k42_minentrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["instit"]) || $this->instit != "")
           $resac = db_query("insert into db_acount values($acount,95,9996,'".AddSlashes(pg_result($resaco,$conresaco,'instit'))."','$this->instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tipovlr"]) || $this->tipovlr != "")
           $resac = db_query("insert into db_acount values($acount,95,14348,'".AddSlashes(pg_result($resaco,$conresaco,'tipovlr'))."','$this->tipovlr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tipoparc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tipoparc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tipoparc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tipoparc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tipoparc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,500,'$tipoparc','E')");
         $resac = db_query("insert into db_acount values($acount,95,500,'','".AddSlashes(pg_result($resaco,$iresaco,'tipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,501,'','".AddSlashes(pg_result($resaco,$iresaco,'descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,502,'','".AddSlashes(pg_result($resaco,$iresaco,'dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,503,'','".AddSlashes(pg_result($resaco,$iresaco,'dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,504,'','".AddSlashes(pg_result($resaco,$iresaco,'maxparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,16693,'','".AddSlashes(pg_result($resaco,0,'minparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");         
         $resac = db_query("insert into db_acount values($acount,95,505,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,506,'','".AddSlashes(pg_result($resaco,$iresaco,'dtvlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,507,'','".AddSlashes(pg_result($resaco,$iresaco,'inflat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,7400,'','".AddSlashes(pg_result($resaco,$iresaco,'descmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,7399,'','".AddSlashes(pg_result($resaco,$iresaco,'descjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,9863,'','".AddSlashes(pg_result($resaco,$iresaco,'descvlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,7581,'','".AddSlashes(pg_result($resaco,$iresaco,'cadtipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,9580,'','".AddSlashes(pg_result($resaco,$iresaco,'k42_minentrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,9996,'','".AddSlashes(pg_result($resaco,$iresaco,'instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,95,14348,'','".AddSlashes(pg_result($resaco,$iresaco,'tipovlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tipoparc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tipoparc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tipoparc = $tipoparc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tipoparc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tipoparc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tipoparc;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoparc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tipoparc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoparc ";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tipoparc.inflat";
     $sql .= "      inner join db_config  on  db_config.codigo = tipoparc.instit";
     $sql .= "      inner join cadtipoparc  on  cadtipoparc.k40_codigo = tipoparc.cadtipoparc";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config  as a on   a.codigo = cadtipoparc.k40_instit";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum = cadtipoparc.k40_db_documento";
     $sql2 = "";
     if($dbwhere==""){
       if($tipoparc!=null ){
         $sql2 .= " where tipoparc.tipoparc = $tipoparc "; 
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
   function sql_query_file ( $tipoparc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoparc ";
     $sql2 = "";
     if($dbwhere==""){
       if($tipoparc!=null ){
         $sql2 .= " where tipoparc.tipoparc = $tipoparc "; 
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