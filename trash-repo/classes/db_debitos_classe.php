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

//MODULO: caixa
//CLASSE DA ENTIDADE debitos
class cl_debitos { 
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
   var $k22_data_dia = null; 
   var $k22_data_mes = null; 
   var $k22_data_ano = null; 
   var $k22_data = null; 
   var $k22_numpre = 0; 
   var $k22_numpar = 0; 
   var $k22_receit = 0; 
   var $k22_dtvenc_dia = null; 
   var $k22_dtvenc_mes = null; 
   var $k22_dtvenc_ano = null; 
   var $k22_dtvenc = null; 
   var $k22_dtoper_dia = null; 
   var $k22_dtoper_mes = null; 
   var $k22_dtoper_ano = null; 
   var $k22_dtoper = null; 
   var $k22_hist = 0; 
   var $k22_numcgm = 0; 
   var $k22_matric = 0; 
   var $k22_inscr = 0; 
   var $k22_tipo = 0; 
   var $k22_vlrhis = 0; 
   var $k22_vlrcor = 0; 
   var $k22_juros = 0; 
   var $k22_multa = 0; 
   var $k22_desconto = 0; 
   var $k22_exerc = 0; 
   var $k22_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k22_data = date = Data 
                 k22_numpre = int4 = Numpre 
                 k22_numpar = int4 = Parcela 
                 k22_receit = int4 = Receita 
                 k22_dtvenc = date = Vencimento 
                 k22_dtoper = date = Data de operação 
                 k22_hist = int4 = Histórico de cálculo 
                 k22_numcgm = int4 = CGM 
                 k22_matric = int4 = Matrícula 
                 k22_inscr = int4 = Inscrição 
                 k22_tipo = int4 = Tipo de débito 
                 k22_vlrhis = float8 = Valor histórico/original 
                 k22_vlrcor = float8 = Valor corrigido 
                 k22_juros = float8 = Juros 
                 k22_multa = float8 = Multa 
                 k22_desconto = float8 = Desconto 
                 k22_exerc = int4 = Exercício do debito 
                 k22_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_debitos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debitos"); 
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
       if($this->k22_data == ""){
         $this->k22_data_dia = ($this->k22_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_data_dia"]:$this->k22_data_dia);
         $this->k22_data_mes = ($this->k22_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_data_mes"]:$this->k22_data_mes);
         $this->k22_data_ano = ($this->k22_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_data_ano"]:$this->k22_data_ano);
         if($this->k22_data_dia != ""){
            $this->k22_data = $this->k22_data_ano."-".$this->k22_data_mes."-".$this->k22_data_dia;
         }
       }
       $this->k22_numpre = ($this->k22_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_numpre"]:$this->k22_numpre);
       $this->k22_numpar = ($this->k22_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_numpar"]:$this->k22_numpar);
       $this->k22_receit = ($this->k22_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_receit"]:$this->k22_receit);
       if($this->k22_dtvenc == ""){
         $this->k22_dtvenc_dia = ($this->k22_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dtvenc_dia"]:$this->k22_dtvenc_dia);
         $this->k22_dtvenc_mes = ($this->k22_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dtvenc_mes"]:$this->k22_dtvenc_mes);
         $this->k22_dtvenc_ano = ($this->k22_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dtvenc_ano"]:$this->k22_dtvenc_ano);
         if($this->k22_dtvenc_dia != ""){
            $this->k22_dtvenc = $this->k22_dtvenc_ano."-".$this->k22_dtvenc_mes."-".$this->k22_dtvenc_dia;
         }
       }
       if($this->k22_dtoper == ""){
         $this->k22_dtoper_dia = ($this->k22_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dtoper_dia"]:$this->k22_dtoper_dia);
         $this->k22_dtoper_mes = ($this->k22_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dtoper_mes"]:$this->k22_dtoper_mes);
         $this->k22_dtoper_ano = ($this->k22_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dtoper_ano"]:$this->k22_dtoper_ano);
         if($this->k22_dtoper_dia != ""){
            $this->k22_dtoper = $this->k22_dtoper_ano."-".$this->k22_dtoper_mes."-".$this->k22_dtoper_dia;
         }
       }
       $this->k22_hist = ($this->k22_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_hist"]:$this->k22_hist);
       $this->k22_numcgm = ($this->k22_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_numcgm"]:$this->k22_numcgm);
       $this->k22_matric = ($this->k22_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_matric"]:$this->k22_matric);
       $this->k22_inscr = ($this->k22_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_inscr"]:$this->k22_inscr);
       $this->k22_tipo = ($this->k22_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_tipo"]:$this->k22_tipo);
       $this->k22_vlrhis = ($this->k22_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_vlrhis"]:$this->k22_vlrhis);
       $this->k22_vlrcor = ($this->k22_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_vlrcor"]:$this->k22_vlrcor);
       $this->k22_juros = ($this->k22_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_juros"]:$this->k22_juros);
       $this->k22_multa = ($this->k22_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_multa"]:$this->k22_multa);
       $this->k22_desconto = ($this->k22_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_desconto"]:$this->k22_desconto);
       $this->k22_exerc = ($this->k22_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_exerc"]:$this->k22_exerc);
       $this->k22_instit = ($this->k22_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_instit"]:$this->k22_instit);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->k22_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k22_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k22_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k22_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k22_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "k22_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_dtoper == null ){ 
       $this->erro_sql = " Campo Data de operação nao Informado.";
       $this->erro_campo = "k22_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_hist == null ){ 
       $this->erro_sql = " Campo Histórico de cálculo nao Informado.";
       $this->erro_campo = "k22_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_numcgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "k22_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "k22_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição nao Informado.";
       $this->erro_campo = "k22_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de débito nao Informado.";
       $this->erro_campo = "k22_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_vlrhis == null ){ 
       $this->erro_sql = " Campo Valor histórico/original nao Informado.";
       $this->erro_campo = "k22_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_vlrcor == null ){ 
       $this->erro_sql = " Campo Valor corrigido nao Informado.";
       $this->erro_campo = "k22_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "k22_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "k22_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_desconto == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "k22_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_exerc == null ){ 
       $this->erro_sql = " Campo Exercício do debito nao Informado.";
       $this->erro_campo = "k22_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k22_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debitos(
                                       k22_data 
                                      ,k22_numpre 
                                      ,k22_numpar 
                                      ,k22_receit 
                                      ,k22_dtvenc 
                                      ,k22_dtoper 
                                      ,k22_hist 
                                      ,k22_numcgm 
                                      ,k22_matric 
                                      ,k22_inscr 
                                      ,k22_tipo 
                                      ,k22_vlrhis 
                                      ,k22_vlrcor 
                                      ,k22_juros 
                                      ,k22_multa 
                                      ,k22_desconto 
                                      ,k22_exerc 
                                      ,k22_instit 
                       )
                values (
                                ".($this->k22_data == "null" || $this->k22_data == ""?"null":"'".$this->k22_data."'")." 
                               ,$this->k22_numpre 
                               ,$this->k22_numpar 
                               ,$this->k22_receit 
                               ,".($this->k22_dtvenc == "null" || $this->k22_dtvenc == ""?"null":"'".$this->k22_dtvenc."'")." 
                               ,".($this->k22_dtoper == "null" || $this->k22_dtoper == ""?"null":"'".$this->k22_dtoper."'")." 
                               ,$this->k22_hist 
                               ,$this->k22_numcgm 
                               ,$this->k22_matric 
                               ,$this->k22_inscr 
                               ,$this->k22_tipo 
                               ,$this->k22_vlrhis 
                               ,$this->k22_vlrcor 
                               ,$this->k22_juros 
                               ,$this->k22_multa 
                               ,$this->k22_desconto 
                               ,$this->k22_exerc 
                               ,$this->k22_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cópia do arrecad diário () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cópia do arrecad diário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cópia do arrecad diário () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update debitos set ";
     $virgula = "";
     if(trim($this->k22_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k22_data_dia"] !="") ){ 
       $sql  .= $virgula." k22_data = '$this->k22_data' ";
       $virgula = ",";
       if(trim($this->k22_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k22_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k22_data_dia"])){ 
         $sql  .= $virgula." k22_data = null ";
         $virgula = ",";
         if(trim($this->k22_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k22_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k22_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_numpre"])){ 
       $sql  .= $virgula." k22_numpre = $this->k22_numpre ";
       $virgula = ",";
       if(trim($this->k22_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k22_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_numpar"])){ 
       $sql  .= $virgula." k22_numpar = $this->k22_numpar ";
       $virgula = ",";
       if(trim($this->k22_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k22_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_receit"])){ 
       $sql  .= $virgula." k22_receit = $this->k22_receit ";
       $virgula = ",";
       if(trim($this->k22_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k22_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k22_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." k22_dtvenc = '$this->k22_dtvenc' ";
       $virgula = ",";
       if(trim($this->k22_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "k22_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k22_dtvenc_dia"])){ 
         $sql  .= $virgula." k22_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k22_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "k22_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k22_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k22_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." k22_dtoper = '$this->k22_dtoper' ";
       $virgula = ",";
       if(trim($this->k22_dtoper) == null ){ 
         $this->erro_sql = " Campo Data de operação nao Informado.";
         $this->erro_campo = "k22_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k22_dtoper_dia"])){ 
         $sql  .= $virgula." k22_dtoper = null ";
         $virgula = ",";
         if(trim($this->k22_dtoper) == null ){ 
           $this->erro_sql = " Campo Data de operação nao Informado.";
           $this->erro_campo = "k22_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k22_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_hist"])){ 
       $sql  .= $virgula." k22_hist = $this->k22_hist ";
       $virgula = ",";
       if(trim($this->k22_hist) == null ){ 
         $this->erro_sql = " Campo Histórico de cálculo nao Informado.";
         $this->erro_campo = "k22_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_numcgm"])){ 
       $sql  .= $virgula." k22_numcgm = $this->k22_numcgm ";
       $virgula = ",";
       if(trim($this->k22_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "k22_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_matric"])){ 
       $sql  .= $virgula." k22_matric = $this->k22_matric ";
       $virgula = ",";
       if(trim($this->k22_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "k22_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_inscr"])){ 
       $sql  .= $virgula." k22_inscr = $this->k22_inscr ";
       $virgula = ",";
       if(trim($this->k22_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição nao Informado.";
         $this->erro_campo = "k22_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_tipo"])){ 
       $sql  .= $virgula." k22_tipo = $this->k22_tipo ";
       $virgula = ",";
       if(trim($this->k22_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de débito nao Informado.";
         $this->erro_campo = "k22_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_vlrhis"])){ 
       $sql  .= $virgula." k22_vlrhis = $this->k22_vlrhis ";
       $virgula = ",";
       if(trim($this->k22_vlrhis) == null ){ 
         $this->erro_sql = " Campo Valor histórico/original nao Informado.";
         $this->erro_campo = "k22_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_vlrcor"])){ 
       $sql  .= $virgula." k22_vlrcor = $this->k22_vlrcor ";
       $virgula = ",";
       if(trim($this->k22_vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor corrigido nao Informado.";
         $this->erro_campo = "k22_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_juros"])){ 
       $sql  .= $virgula." k22_juros = $this->k22_juros ";
       $virgula = ",";
       if(trim($this->k22_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "k22_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_multa"])){ 
       $sql  .= $virgula." k22_multa = $this->k22_multa ";
       $virgula = ",";
       if(trim($this->k22_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "k22_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_desconto"])){ 
       $sql  .= $virgula." k22_desconto = $this->k22_desconto ";
       $virgula = ",";
       if(trim($this->k22_desconto) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "k22_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_exerc"])){ 
       $sql  .= $virgula." k22_exerc = $this->k22_exerc ";
       $virgula = ",";
       if(trim($this->k22_exerc) == null ){ 
         $this->erro_sql = " Campo Exercício do debito nao Informado.";
         $this->erro_campo = "k22_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_instit"])){ 
       $sql  .= $virgula." k22_instit = $this->k22_instit ";
       $virgula = ",";
       if(trim($this->k22_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k22_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cópia do arrecad diário nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cópia do arrecad diário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from debitos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cópia do arrecad diário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cópia do arrecad diário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:debitos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="debitos.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debitos ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where debitos.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debitos ";
     $sql2 = "";
     if($dbwhere==""){
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